<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\TeamUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\User\TeamUsers\Business\UpdateStatusTeamUserBusiness;
use App\Features\User\Users\Contracts\UserUpdateStatusServiceInterface;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class UpdateStatusTeamUserBusinessTest extends UnitBaseTestCase
{
    private MockObject|UserUpdateStatusServiceInterface $userUpdateStatusServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userUpdateStatusServiceMock = $this->createMock(UserUpdateStatusServiceInterface::class);
    }

    public function getUpdateStatusTeamUserBusiness(): UpdateStatusTeamUserBusiness
    {
        return new UpdateStatusTeamUserBusiness($this->userUpdateStatusServiceMock);
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::TEAM_USERS_ADMIN_MASTER_UPDATE->value],
            'Project Manager' => [RulesEnum::TEAM_USERS_PROJECT_MANAGER_UPDATE->value],
        ];
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_to_update_status_user(
        string $rule,
    ): void
    {
        $updateStatusTeamUserBusiness = $this->getUpdateStatusTeamUserBusiness();

        $updateStatusTeamUserBusiness->setPolicy(
            new Policy([$rule])
        );

        $result = $updateStatusTeamUserBusiness->handle(Uuid::uuid4Generate());

        $this->assertIsObject($result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateStatusTeamUserBusiness = $this->getUpdateStatusTeamUserBusiness();

        $updateStatusTeamUserBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateStatusTeamUserBusiness->handle(Uuid::uuid4Generate());
    }
}
