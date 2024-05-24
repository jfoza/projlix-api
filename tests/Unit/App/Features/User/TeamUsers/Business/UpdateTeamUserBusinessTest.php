<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\TeamUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Models\Project;
use App\Features\User\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\User\TeamUsers\Business\UpdateTeamUserBusiness;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\Responses\SavedTeamUserResponse;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\DTO\UserDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Mocks\ProfileMocks;
use Tests\Unit\Mocks\ProjectMocks;
use Tests\Unit\Mocks\UserMocks;
use Tests\Unit\UnitBaseTestCase;

class UpdateTeamUserBusinessTest extends UnitBaseTestCase
{
    private MockObject|UsersRepositoryInterface     $usersRepositoryMock;
    private MockObject|TeamUsersRepositoryInterface $teamUsersRepositoryMock;
    private MockObject|ProfilesRepositoryInterface  $profilesRepositoryMock;
    private MockObject|ProjectsRepositoryInterface  $projectsRepositoryMock;

    private MockObject|UserDTO $userDTOMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock     = $this->createMock(UsersRepositoryInterface::class);
        $this->teamUsersRepositoryMock = $this->createMock(TeamUsersRepositoryInterface::class);
        $this->profilesRepositoryMock  = $this->createMock(ProfilesRepositoryInterface::class);
        $this->projectsRepositoryMock  = $this->createMock(ProjectsRepositoryInterface::class);

        $this->userDTOMock = $this->createMock(UserDTO::class);

        $this->setUserDto();
    }

    public function getUpdateTeamUserBusiness(): UpdateTeamUserBusiness
    {
        return new UpdateTeamUserBusiness(
            $this->usersRepositoryMock,
            $this->teamUsersRepositoryMock,
            $this->profilesRepositoryMock,
            $this->projectsRepositoryMock,
        );
    }

    public function setUserDto(): void
    {
        $this->userDTOMock->id = Uuid::uuid4Generate();
        $this->userDTOMock->name = 'John Doe';
        $this->userDTOMock->email = 'john-doe@email.com';
        $this->userDTOMock->profileId = Uuid::uuid4Generate();
        $this->userDTOMock->projectsId = [];
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::TEAM_USERS_ADMIN_MASTER_UPDATE->value],
            'Project Manager' => [RulesEnum::TEAM_USERS_PROJECT_MANAGER_UPDATE->value],
            'Team Leader'     => [RulesEnum::PROJECTS_TEAM_LEADER_UPDATE->value],
        ];
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_to_create_new_team_user(
        string $rule,
    ): void
    {
        $projects = ProjectMocks::getAllProjects();

        $profileInPayload = ProfileMocks::getProjectMember();

        $teamUser = UserMocks::showTeamUser(
            null,
            null,
            $projects
        );

        $authUserMock = $this->getAuthUserMock();

        $authUserMock
            ->teamUser
            ->setProjects($teamUser->projects);

        $this->userDTOMock->projectsId = $projects->pluck(Project::ID)->toArray();
        $this->userDTOMock->profileId  = $profileInPayload->id;

        $updateTeamUserBusiness = $this->getUpdateTeamUserBusiness();

        $updateTeamUserBusiness->setPolicy(
            new Policy([$rule])
        );

        $updateTeamUserBusiness->setAuthenticatedUser($authUserMock);

        $this
            ->teamUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn($teamUser);

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfileMocks::getProjectMember());

        $this
            ->projectsRepositoryMock
            ->method('findByIds')
            ->willReturn($projects);

        $this
            ->projectsRepositoryMock
            ->method('findAllWithoutFilters')
            ->willReturn($projects);

        $this
            ->usersRepositoryMock
            ->method('update')
            ->willReturn(UserMocks::getUserSaved());

        $result = $updateTeamUserBusiness->handle($this->userDTOMock);

        $this->assertInstanceOf(SavedTeamUserResponse::class, $result);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_user_not_exists(
        string $rule,
    ): void
    {
        $projects = ProjectMocks::getAllProjects();

        $authUserMock = $this->getAuthUserMock();

        $this->userDTOMock->projectsId = $projects->pluck(Project::ID)->toArray();

        $updateTeamUserBusiness = $this->getUpdateTeamUserBusiness();

        $updateTeamUserBusiness->setPolicy(
            new Policy([$rule])
        );

        $updateTeamUserBusiness->setAuthenticatedUser($authUserMock);

        $this
            ->teamUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $updateTeamUserBusiness->handle($this->userDTOMock);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_email_already_exists(
        string $rule,
    ): void
    {
        $projects = ProjectMocks::getAllProjects();

        $teamUser1 = UserMocks::showTeamUser(
            null,
            null,
            $projects
        );

        $teamUser2 = UserMocks::showTeamUser(
            Uuid::uuid4Generate(),
            null,
            $projects
        );

        $authUserMock = $this->getAuthUserMock();

        $authUserMock
            ->teamUser
            ->setProjects($teamUser1->projects);

        $this->userDTOMock->projectsId = $projects->pluck(Project::ID)->toArray();

        $updateTeamUserBusiness = $this->getUpdateTeamUserBusiness();

        $updateTeamUserBusiness->setPolicy(
            new Policy([$rule])
        );

        $updateTeamUserBusiness->setAuthenticatedUser($authUserMock);

        $this
            ->teamUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn($teamUser1);

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn($teamUser2);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::EMAIL_ALREADY_EXISTS));

        $updateTeamUserBusiness->handle($this->userDTOMock);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_profile_not_exists(
        string $rule,
    ): void
    {
        $projects = ProjectMocks::getAllProjects();

        $teamUser = UserMocks::showTeamUser(
            null,
            null,
            $projects
        );

        $authUserMock = $this->getAuthUserMock();

        $authUserMock
            ->teamUser
            ->setProjects($teamUser->projects);

        $this->userDTOMock->projectsId = $projects->pluck(Project::ID)->toArray();

        $updateTeamUserBusiness = $this->getUpdateTeamUserBusiness();

        $updateTeamUserBusiness->setPolicy(
            new Policy([$rule])
        );

        $updateTeamUserBusiness->setAuthenticatedUser($authUserMock);

        $this
            ->teamUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn($teamUser);

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_FOUND));

        $updateTeamUserBusiness->handle($this->userDTOMock);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_project_not_exists(
        string $rule,
    ): void
    {
        $projects = ProjectMocks::getAllProjects();

        $teamUser = UserMocks::showTeamUser(
            null,
            null,
            $projects
        );

        $authUserMock = $this->getAuthUserMock();

        $authUserMock
            ->teamUser
            ->setProjects($teamUser->projects);

        $this->userDTOMock->projectsId = $projects->pluck(Project::ID)->toArray();

        $updateTeamUserBusiness = $this->getUpdateTeamUserBusiness();

        $updateTeamUserBusiness->setPolicy(
            new Policy([$rule])
        );

        $updateTeamUserBusiness->setAuthenticatedUser($authUserMock);

        $this
            ->teamUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn($teamUser);

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfileMocks::getProjectMember());

        $this
            ->projectsRepositoryMock
            ->method('findByIds')
            ->willReturn(Collection::make([
                (object) ([Project::ID => Uuid::uuid4Generate()]),
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_FOUND));

        $updateTeamUserBusiness->handle($this->userDTOMock);
    }

    #[DataProvider('dataProviderRules')]
    public function test_must_return_exception_if_a_higher_hierarchy_profile_is_provided(
        string $rule
    ): void
    {
        $projects = ProjectMocks::getAllProjects();

        $teamUser = UserMocks::showTeamUser(
            null,
            null,
            $projects
        );

        $authUserMock = $this->getAuthUserMock();

        $authUserMock
            ->teamUser
            ->setProjects($teamUser->projects);

        $this->userDTOMock->projectsId = $projects->pluck(Project::ID)->toArray();

        $updateTeamUserBusiness = $this->getUpdateTeamUserBusiness();

        $updateTeamUserBusiness->setPolicy(
            new Policy([$rule])
        );

        $updateTeamUserBusiness->setAuthenticatedUser($authUserMock);

        $this
            ->teamUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn($teamUser);

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfileMocks::getAdminMaster());

        $this
            ->projectsRepositoryMock
            ->method('findByIds')
            ->willReturn($projects);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_ALLOWED));

        $updateTeamUserBusiness->handle($this->userDTOMock);
    }

    public function test_should_return_exception_if_user_does_not_have_access_to_project()
    {
        $authUserMock = $this->getAuthUserMock();

        $authUserMock->teamUser->setProjects(
            Collection::make([
                (object)([
                    Project::ID => Uuid::uuid4Generate(),
                    Project::NAME => 'Project 200',
                ])
            ])
        );

        $projects = ProjectMocks::getAllProjects();

        $teamUser = UserMocks::showTeamUser(
            null,
            null,
            $projects
        );

        $this->userDTOMock->projectsId = $projects->pluck(Project::ID)->toArray();

        $updateTeamUserBusiness = $this->getUpdateTeamUserBusiness();

        $updateTeamUserBusiness->setPolicy(
            new Policy([RulesEnum::PROJECTS_TEAM_LEADER_UPDATE->value])
        );

        $updateTeamUserBusiness->setAuthenticatedUser($authUserMock);

        $this
            ->teamUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn($teamUser);

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfileMocks::getProjectMember());

        $this
            ->projectsRepositoryMock
            ->method('findByIds')
            ->willReturn($projects);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_ALLOWED));

        $updateTeamUserBusiness->handle($this->userDTOMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateTeamUserBusiness = $this->getUpdateTeamUserBusiness();

        $updateTeamUserBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateTeamUserBusiness->handle($this->userDTOMock);
    }
}
