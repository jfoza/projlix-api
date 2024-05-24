<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\TeamUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Base\Pagination\PaginationOrder;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Models\Project;
use App\Features\User\TeamUsers\Business\FindAllTeamUsersBusiness;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\DTO\TeamUsersFiltersDTO;
use App\Features\User\Users\Models\User;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class FindAllTeamUsersBusinessTest extends UnitBaseTestCase
{
    private MockObject|TeamUsersRepositoryInterface $teamUsersRepositoryMock;
    private MockObject|ProjectsRepositoryInterface $projectsRepositoryMock;

    private MockObject|TeamUsersFiltersDTO $teamUsersFiltersDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teamUsersRepositoryMock = $this->createMock(TeamUsersRepositoryInterface::class);
        $this->projectsRepositoryMock  = $this->createMock(ProjectsRepositoryInterface::class);

        $this->teamUsersFiltersDTO = $this->createMock(TeamUsersFiltersDTO::class);
    }

    public function getFindAllTeamUsersBusiness(): FindAllTeamUsersBusiness
    {
        return new FindAllTeamUsersBusiness(
            $this->teamUsersRepositoryMock,
            $this->projectsRepositoryMock,
        );
    }

    public function getUsers(): Collection
    {
        return Collection::make([
            [User::ID]
        ]);
    }

    public function getPaginatedUsersList(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $this->getUsers(),
            10,
            10,
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

    public static function dataProviderRulesValidateAccessToProject(): array
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
        $findAllTeamUsersBusiness = $this->getFindAllTeamUsersBusiness();

        $findAllTeamUsersBusiness->setPolicy(
            new Policy([$rule])
        );

        $findAllTeamUsersBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this
            ->teamUsersRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $result = $findAllTeamUsersBusiness->handle($this->teamUsersFiltersDTO);

        $this->assertInstanceOf(Collection::class, $result);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_paginated_team_users_list(
        string $rule,
    ): void
    {
        $findAllTeamUsersBusiness = $this->getFindAllTeamUsersBusiness();

        $findAllTeamUsersBusiness->setPolicy(
            new Policy([$rule])
        );

        $findAllTeamUsersBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this->teamUsersFiltersDTO->paginationOrder = new PaginationOrder();

        $this->teamUsersFiltersDTO->paginationOrder->setPage(1);
        $this->teamUsersFiltersDTO->paginationOrder->setPerPage(10);

        $this
            ->teamUsersRepositoryMock
            ->method('findAll')
            ->willReturn($this->getPaginatedUsersList());

        $result = $findAllTeamUsersBusiness->handle($this->teamUsersFiltersDTO);

        $this->assertInstanceOf(LengthAwarePaginatorContract::class, $result);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_to_list_team_users_with_project_id(
        string $rule,
    ): void
    {
        $findAllTeamUsersBusiness = $this->getFindAllTeamUsersBusiness();

        $findAllTeamUsersBusiness->setPolicy(
            new Policy([$rule])
        );

        $authUserMock = $this->getAuthUserMock();

        $projectId = Uuid::uuid4Generate();

        $authUserMock->teamUser->setProjects(
            Collection::make([
                [Project::ID => $projectId]
            ])
        );

        $findAllTeamUsersBusiness->setAuthenticatedUser($authUserMock);

        $this->teamUsersFiltersDTO->projectsId = [$projectId];

        $this
            ->teamUsersRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $this
            ->projectsRepositoryMock
            ->method('findByIds')
            ->willReturn(Collection::make([
                [Project::ID => $projectId]
            ]));

        $result = $findAllTeamUsersBusiness->handle($this->teamUsersFiltersDTO);

        $this->assertInstanceOf(Collection::class, $result);
    }

    #[DataProvider('dataProviderRulesValidateAccessToProject')]
    public function test_should_return_exception_if_user_does_not_have_access_to_project(
        string $rule,
    ): void
    {
        $findAllTeamUsersBusiness = $this->getFindAllTeamUsersBusiness();

        $findAllTeamUsersBusiness->setPolicy(
            new Policy([$rule])
        );

        $findAllTeamUsersBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $projectId = Uuid::uuid4Generate();

        $this->teamUsersFiltersDTO->projectsId = [$projectId];

        $this
            ->teamUsersRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $this
            ->projectsRepositoryMock
            ->method('findByIds')
            ->willReturn(Collection::make([
                [Project::ID => $projectId]
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_ALLOWED_IN_TEAM_USERS));

        $findAllTeamUsersBusiness->handle($this->teamUsersFiltersDTO);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllTeamUsersBusiness = $this->getFindAllTeamUsersBusiness();

        $findAllTeamUsersBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllTeamUsersBusiness->handle($this->teamUsersFiltersDTO);
    }
}
