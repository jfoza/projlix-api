<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Project\Projects\Business\CreateProjectBusiness;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Models\Project;
use App\Features\Project\Projects\Responses\SavedProjectsResponse;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\Models\TeamUser;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class CreateProjectBusinessTest extends UnitBaseTestCase
{
    private MockObject|ProjectsRepositoryInterface  $projectsRepositoryMock;
    private MockObject|TeamUsersRepositoryInterface $teamUsersRepositoryMock;
    private MockObject|ProjectDTO $projectDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectsRepositoryMock  = $this->createMock(ProjectsRepositoryInterface::class);
        $this->teamUsersRepositoryMock = $this->createMock(TeamUsersRepositoryInterface::class);

        $this->projectDtoMock = $this->createMock(ProjectDTO::class);

        $this->projectDtoMock->name = 'name';
        $this->projectDtoMock->description = 'description';
    }

    public function getCreateProjectBusiness(): CreateProjectBusiness
    {
        return new CreateProjectBusiness(
            $this->projectsRepositoryMock,
            $this->teamUsersRepositoryMock,
        );
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::PROJECTS_ADMIN_MASTER_INSERT->value],
            'Project Manager' => [RulesEnum::PROJECTS_PROJECT_MANAGER_INSERT->value],
            'Team Leader'     => [RulesEnum::PROJECTS_TEAM_LEADER_INSERT->value],
        ];
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_to_create_new_project(
        string $rule
    ): void
    {
        $createProjectBusiness = $this->getCreateProjectBusiness();

        $createProjectBusiness->setPolicy(
            new Policy([$rule])
        );

        $createProjectBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $teamUsersId = [
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
        ];

        $this->projectDtoMock->teamUsers = $teamUsersId;

        $this
            ->projectsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->teamUsersRepositoryMock
            ->method('findByTeamUsersIds')
            ->willReturn(Collection::make([
                (object) ([TeamUser::ID => Uuid::uuid4Generate(), 'team_user_id' => $teamUsersId[0]]),
                (object) ([TeamUser::ID => Uuid::uuid4Generate(), 'team_user_id' => $teamUsersId[1]]),
            ]));

        $this
            ->projectsRepositoryMock
            ->method('create')
            ->willReturn((object) ([
                Project::ID => Uuid::uuid4Generate(),
                Project::NAME => 'name',
                Project::DESCRIPTION => 'description',
            ]));

        $result = $createProjectBusiness->handle($this->projectDtoMock);

        $this->assertInstanceOf(SavedProjectsResponse::class, $result);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_there_is_already_a_project_with_the_same_name(
        string $rule
    ): void
    {
        $createProjectBusiness = $this->getCreateProjectBusiness();

        $createProjectBusiness->setPolicy(
            new Policy([$rule])
        );

        $createProjectBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $teamUsersId = [
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
        ];

        $this->projectDtoMock->teamUsers = $teamUsersId;

        $this
            ->projectsRepositoryMock
            ->method('findByName')
            ->willReturn((object) ([Project::ID => Uuid::uuid4Generate()]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::REGISTER_NAME_ALREADY_EXISTS));

        $createProjectBusiness->handle($this->projectDtoMock);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_team_user_id_not_exists(
        string $rule
    ): void
    {
        $createProjectBusiness = $this->getCreateProjectBusiness();

        $createProjectBusiness->setPolicy(
            new Policy([$rule])
        );

        $createProjectBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this->projectDtoMock->teamUsers = [
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
        ];

        $this
            ->projectsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->teamUsersRepositoryMock
            ->method('findByTeamUsersIds')
            ->willReturn(Collection::make([
                (object) ([TeamUser::ID => Uuid::uuid4Generate(), 'team_user_id' => Uuid::uuid4Generate()]),
                (object) ([TeamUser::ID => Uuid::uuid4Generate(), 'team_user_id' => Uuid::uuid4Generate()]),
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $createProjectBusiness->handle($this->projectDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createProjectBusiness = $this->getCreateProjectBusiness();

        $createProjectBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createProjectBusiness->handle($this->projectDtoMock);
    }
}
