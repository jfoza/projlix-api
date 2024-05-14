<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Project\Projects\Models\Project;
use App\Features\Project\Sections\Business\FindAllSectionsBusiness;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\DTO\SectionsFiltersDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class FindAllSectionsBusinessTest extends UnitBaseTestCase
{
    private MockObject|SectionsRepositoryInterface $sectionsRepositoryMock;
    private MockObject|SectionsFiltersDTO $sectionsFiltersDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sectionsRepositoryMock = $this->createMock(SectionsRepositoryInterface::class);
        $this->sectionsFiltersDtoMock = $this->createMock(SectionsFiltersDTO::class);
    }

    public function getFindAllSectionsBusiness(): FindAllSectionsBusiness
    {
        return new FindAllSectionsBusiness($this->sectionsRepositoryMock);
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::SECTIONS_ADMIN_MASTER_VIEW->value],
            'Project Manager' => [RulesEnum::SECTIONS_PROJECT_MANAGER_VIEW->value],
            'Team Leader'     => [RulesEnum::SECTIONS_TEAM_LEADER_VIEW->value],
            'Project Member'  => [RulesEnum::SECTIONS_PROJECT_MEMBER_VIEW->value],
        ];
    }

    public static function dataProviderValidationRules(): array
    {
        return [
            'Team Leader'     => [RulesEnum::SECTIONS_TEAM_LEADER_VIEW->value],
            'Project Member'  => [RulesEnum::SECTIONS_PROJECT_MEMBER_VIEW->value],
        ];
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_find_all_sections(
        string $rule,
    ): void
    {
        $findAllSectionsBusiness = $this->getFindAllSectionsBusiness();

        $findAllSectionsBusiness->setPolicy(
            new Policy([$rule])
        );

        $project = (object) ([
            Project::ID => Uuid::uuid4Generate(),
        ]);

        $authUserMock = $this->getAuthUserMock();

        $authUserMock->teamUser->setProjects(Collection::make([$project]));

        $findAllSectionsBusiness->setAuthenticatedUser($authUserMock);

        $this->sectionsFiltersDtoMock->projectId = $project->id;

        $this
            ->sectionsRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $result = $findAllSectionsBusiness->handle($this->sectionsFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $result);
    }


    #[DataProvider('dataProviderValidationRules')]
    public function test_must_return_exception_if_the_authenticated_user_does_not_have_access_to_the_project(
        string $rule,
    ): void
    {
        $findAllSectionsBusiness = $this->getFindAllSectionsBusiness();

        $findAllSectionsBusiness->setPolicy(
            new Policy([$rule])
        );

        $authUserMock = $this->getAuthUserMock();

        $findAllSectionsBusiness->setAuthenticatedUser($authUserMock);

        $this->sectionsFiltersDtoMock->projectId = Uuid::uuid4Generate();

        $this
            ->sectionsRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_ALLOWED_IN_SECTION));

        $findAllSectionsBusiness->handle($this->sectionsFiltersDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllSectionsBusiness = $this->getFindAllSectionsBusiness();

        $findAllSectionsBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllSectionsBusiness->handle($this->sectionsFiltersDtoMock);
    }
}
