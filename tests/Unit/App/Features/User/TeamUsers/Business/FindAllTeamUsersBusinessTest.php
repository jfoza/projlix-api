<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\TeamUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Base\Pagination\PaginationOrder;
use App\Features\User\TeamUsers\Business\FindAllTeamUsersBusiness;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\Users\DTO\UsersFiltersDTO;
use App\Features\User\Users\Models\User;
use App\Shared\Enums\RulesEnum;
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

    private MockObject|UsersFiltersDTO $usersFiltersDTOMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teamUsersRepositoryMock = $this->createMock(TeamUsersRepositoryInterface::class);

        $this->usersFiltersDTOMock = $this->createMock(UsersFiltersDTO::class);
    }

    public function getFindAllTeamUsersBusiness(): FindAllTeamUsersBusiness
    {
        return new FindAllTeamUsersBusiness(
            $this->teamUsersRepositoryMock,
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

        $result = $findAllTeamUsersBusiness->handle($this->usersFiltersDTOMock);

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

        $this->usersFiltersDTOMock->paginationOrder = new PaginationOrder();

        $this->usersFiltersDTOMock->paginationOrder->setPage(1);
        $this->usersFiltersDTOMock->paginationOrder->setPerPage(10);

        $this
            ->teamUsersRepositoryMock
            ->method('findAll')
            ->willReturn($this->getPaginatedUsersList());

        $result = $findAllTeamUsersBusiness->handle($this->usersFiltersDTOMock);

        $this->assertInstanceOf(LengthAwarePaginatorContract::class, $result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllTeamUsersBusiness = $this->getFindAllTeamUsersBusiness();

        $findAllTeamUsersBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllTeamUsersBusiness->handle($this->usersFiltersDTOMock);
    }
}
