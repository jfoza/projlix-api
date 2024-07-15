<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Project\Projects\Business\UpdateProjectInfoBusiness;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Models\Project;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class UpdateProjectBusinessTest extends UnitBaseTestCase
{
    private MockObject|ProjectsRepositoryInterface  $projectsRepositoryMock;
    private MockObject|ProjectDTO $projectDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectsRepositoryMock  = $this->createMock(ProjectsRepositoryInterface::class);

        $this->projectDtoMock = $this->createMock(ProjectDTO::class);

        $this->projectDtoMock->name = 'name';
        $this->projectDtoMock->description = 'description';
    }

    public function getUpdateProjectBusiness(): UpdateProjectInfoBusiness
    {
        return new UpdateProjectInfoBusiness(
            $this->projectsRepositoryMock,
        );
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::PROJECTS_ADMIN_MASTER_UPDATE->value],
            'Project Manager' => [RulesEnum::PROJECTS_PROJECT_MANAGER_UPDATE->value],
            'Team Leader'     => [RulesEnum::PROJECTS_TEAM_LEADER_UPDATE->value],
        ];
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_to_create_new_project(
        string $rule
    ): void
    {
        $updateProjectBusiness = $this->getUpdateProjectBusiness();

        $updateProjectBusiness->setPolicy(
            new Policy([$rule])
        );

        $authUserMock = $this->getAuthUserMock();

        $project = $authUserMock->teamUser->getProjects()->first();

        $updateProjectBusiness->setAuthenticatedUser($authUserMock);

        $this->projectDtoMock->id = $project->id;

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn($project);

        $this
            ->projectsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $result = $updateProjectBusiness->handle($this->projectDtoMock);

        $this->assertIsObject($result);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_project_not_found(
        string $rule
    ): void
    {
        $updateProjectBusiness = $this->getUpdateProjectBusiness();

        $updateProjectBusiness->setPolicy(
            new Policy([$rule])
        );

        $authUserMock = $this->getAuthUserMock();

        $project = $authUserMock->teamUser->getProjects()->first();

        $updateProjectBusiness->setAuthenticatedUser($authUserMock);

        $this->projectDtoMock->id = $project->id;

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_FOUND));

        $updateProjectBusiness->handle($this->projectDtoMock);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_there_is_already_a_project_with_the_same_name(
        string $rule
    ): void
    {
        $updateProjectBusiness = $this->getUpdateProjectBusiness();

        $updateProjectBusiness->setPolicy(
            new Policy([$rule])
        );

        $authUserMock = $this->getAuthUserMock();

        $project = $authUserMock->teamUser->getProjects()->first();

        $updateProjectBusiness->setAuthenticatedUser($authUserMock);

        $this->projectDtoMock->id = $project->id;

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn($project);

        $this
            ->projectsRepositoryMock
            ->method('findByName')
            ->willReturn((object) ([
                Project::ID   => Uuid::uuid4Generate(),
                Project::NAME => $this->projectDtoMock->name
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::REGISTER_NAME_ALREADY_EXISTS));

        $updateProjectBusiness->handle($this->projectDtoMock);
    }

    public function test_must_return_exception_if_the_authenticated_user_does_not_have_access_to_the_project()
    {
        $updateProjectBusiness = $this->getUpdateProjectBusiness();

        $updateProjectBusiness->setPolicy(
            new Policy([RulesEnum::PROJECTS_TEAM_LEADER_UPDATE->value])
        );

        $authUserMock = $this->getAuthUserMock();

        $updateProjectBusiness->setAuthenticatedUser($authUserMock);

        $this->projectDtoMock->id = Uuid::uuid4Generate();

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Project::ID   => Uuid::uuid4Generate(),
                Project::NAME => $this->projectDtoMock->name
            ]));

        $this
            ->projectsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_ALLOWED));

        $updateProjectBusiness->handle($this->projectDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateProjectBusiness = $this->getUpdateProjectBusiness();

        $updateProjectBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateProjectBusiness->handle($this->projectDtoMock);
    }
}
