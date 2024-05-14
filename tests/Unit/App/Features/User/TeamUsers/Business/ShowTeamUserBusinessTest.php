<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\TeamUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\User\TeamUsers\Business\ShowTeamUserBusiness;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Mocks\UserMocks;
use Tests\Unit\Resources\AuthUserMock;
use Tests\Unit\UnitBaseTestCase;

class ShowTeamUserBusinessTest extends UnitBaseTestCase
{
    private MockObject|TeamUsersRepositoryInterface $teamUsersRepositoryMock;

    private AuthUserMock $authUserMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teamUsersRepositoryMock = $this->createMock(TeamUsersRepositoryInterface::class);

        $this->authUserMock = $this->getAuthUserMock();
    }

    public function getShowTeamUserBusiness(): ShowTeamUserBusiness
    {
        return new ShowTeamUserBusiness(
            $this->teamUsersRepositoryMock,
        );
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::TEAM_USERS_ADMIN_MASTER_VIEW->value],
            'Project Manager' => [RulesEnum::TEAM_USERS_PROJECT_MANAGER_VIEW->value],
            'Team Leader'     => [RulesEnum::TEAM_USERS_TEAM_LEADER_VIEW->value],
            'Project Member'  => [RulesEnum::TEAM_USERS_PROJECT_MEMBER_VIEW->value],
        ];
    }

    public static function dataProviderValidateAccess(): array
    {
        return [
            'Team Leader'     => [RulesEnum::TEAM_USERS_TEAM_LEADER_VIEW->value],
            'Project Member'  => [RulesEnum::TEAM_USERS_PROJECT_MEMBER_VIEW->value],
        ];
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_to_list_team_users(
        string $rule,
    ): void
    {
        $showTeamUserBusiness = $this->getShowTeamUserBusiness();

        $showTeamUserBusiness->setPolicy(
            new Policy([$rule])
        );

        $teamUser = UserMocks::showTeamUser();

        $this
            ->authUserMock
            ->teamUser
            ->setProjects($teamUser->projects);

        $showTeamUserBusiness->setAuthenticatedUser($this->authUserMock);

        $this
            ->teamUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn($teamUser);

        $result = $showTeamUserBusiness->handle(Uuid::uuid4Generate());

        $this->assertIsObject($result);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_exception_if_team_user_not_found(
        string $rule,
    ): void
    {
        $showTeamUserBusiness = $this->getShowTeamUserBusiness();

        $showTeamUserBusiness->setPolicy(
            new Policy([$rule])
        );

        $this
            ->teamUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $showTeamUserBusiness->handle(Uuid::uuid4Generate());
    }

    #[DataProvider('dataProviderValidateAccess')]
    public function test_should_return_exception_if_trying_to_access_users_from_unlinked_projects(
        string $rule,
    ): void
    {
        $showTeamUserBusiness = $this->getShowTeamUserBusiness();

        $showTeamUserBusiness->setPolicy(
            new Policy([$rule])
        );

        $teamUser = UserMocks::showTeamUser();

        $showTeamUserBusiness->setAuthenticatedUser($this->authUserMock);

        $this
            ->teamUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn($teamUser);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_ALLOWED));

        $showTeamUserBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showTeamUserBusiness = $this->getShowTeamUserBusiness();

        $showTeamUserBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showTeamUserBusiness->handle(Uuid::uuid4Generate());
    }
}
