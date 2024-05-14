<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\AdminUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Base\Pagination\PaginationOrder;
use App\Features\User\AdminUsers\Business\FindAllAdminUsersBusiness;
use App\Features\User\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\User\Users\DTO\UsersFiltersDTO;
use App\Features\User\Users\Models\User;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class FindAllAdminUsersBusinessTest extends UnitBaseTestCase
{
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;
    private MockObject|UsersFiltersDTO $usersFiltersDTOMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepositoryInterface::class);
        $this->usersFiltersDTOMock = $this->createMock(UsersFiltersDTO::class);
    }

    public function getFindAllAdminUsersBusiness(): FindAllAdminUsersBusiness
    {
        return new FindAllAdminUsersBusiness(
            $this->adminUsersRepositoryMock,
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

    public function test_should_return_admin_users_list()
    {
        $findAllAdminUsersBusiness = $this->getFindAllAdminUsersBusiness();

        $findAllAdminUsersBusiness->setPolicy(
            new Policy([RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value])
        );

        $this
            ->adminUsersRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $result = $findAllAdminUsersBusiness->handle($this->usersFiltersDTOMock);

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_should_return_paginated_admin_users_list()
    {
        $findAllAdminUsersBusiness = $this->getFindAllAdminUsersBusiness();

        $findAllAdminUsersBusiness->setPolicy(
            new Policy([RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value])
        );

        $this->usersFiltersDTOMock->paginationOrder = new PaginationOrder();

        $this->usersFiltersDTOMock->paginationOrder->setPage(1);
        $this->usersFiltersDTOMock->paginationOrder->setPerPage(10);

        $this
            ->adminUsersRepositoryMock
            ->method('findAll')
            ->willReturn($this->getPaginatedUsersList());

        $result = $findAllAdminUsersBusiness->handle($this->usersFiltersDTOMock);

        $this->assertInstanceOf(LengthAwarePaginatorContract::class, $result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllAdminUsersBusiness = $this->getFindAllAdminUsersBusiness();

        $findAllAdminUsersBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllAdminUsersBusiness->handle($this->usersFiltersDTOMock);
    }
}
