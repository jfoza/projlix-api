<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\Profiles\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\User\Profiles\Business\FindAllProfilesBusiness;
use App\Features\User\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\User\Profiles\DTO\ProfilesFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class FindAllProfilesBusinessTest extends UnitBaseTestCase
{
    private MockObject|ProfilesRepositoryInterface $profilesRepositoryMock;
    private MockObject|ProfilesFiltersDTO $profilesFiltersDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profilesRepositoryMock = $this->createMock(ProfilesRepositoryInterface::class);
        $this->profilesFiltersDtoMock = $this->createMock(ProfilesFiltersDTO::class);
    }

    public function getFindAllProfilesBusiness(): FindAllProfilesBusiness
    {
        return new FindAllProfilesBusiness(
            $this->profilesRepositoryMock,
        );
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::PROFILES_ADMIN_MASTER_VIEW->value],
            'Project Manager' => [RulesEnum::PROFILES_PROJECT_MANAGER_VIEW->value],
            'Team Leader'     => [RulesEnum::PROFILES_TEAM_LEADER_VIEW->value],
            'Project Member'  => [RulesEnum::PROFILES_PROJECT_MEMBER_VIEW->value],
        ];
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_profiles_list(
        string $rule
    ): void
    {
        $findAllProfilesBusiness = $this->getFindAllProfilesBusiness();

        $findAllProfilesBusiness->setPolicy(
            new Policy([$rule])
        );

        $findAllProfilesBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this
            ->profilesRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $result = $findAllProfilesBusiness->handle($this->profilesFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllProfilesBusiness = $this->getFindAllProfilesBusiness();

        $findAllProfilesBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllProfilesBusiness->handle($this->profilesFiltersDtoMock);
    }
}
