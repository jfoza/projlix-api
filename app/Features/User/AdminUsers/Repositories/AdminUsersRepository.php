<?php
declare(strict_types=1);

namespace App\Features\User\AdminUsers\Repositories;

use App\Features\Base\Traits\BuilderTrait;
use App\Features\User\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\User\AdminUsers\Models\AdminUser;
use App\Features\User\AdminUsers\Traits\AdminUsersTrait;
use App\Features\User\Users\DTO\UsersFiltersDTO;
use App\Features\User\Users\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminUsersRepository implements AdminUsersRepositoryInterface
{
    public function __construct(
        private readonly AdminUser $adminUser,
    ) {}

    use BuilderTrait, AdminUsersTrait;

    public function findAll(UsersFiltersDTO $usersFiltersDTO): LengthAwarePaginator|Collection
    {
        $builder = $this->getBaseQueryFilters($usersFiltersDTO);

        return $this->paginateOrGet(
            $builder,
            $usersFiltersDTO->paginationOrder
        );
    }

    public function findByUserId(string $userId): ?object
    {
        return $this
            ->getBaseQuery()
            ->where(User::tableField(User::ID), $userId)
            ->first();
    }

    public function create(string $userId): object
    {
        return $this->adminUser->create([AdminUser::USER_ID => $userId]);
    }
}
