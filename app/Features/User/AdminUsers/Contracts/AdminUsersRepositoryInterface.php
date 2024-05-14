<?php

namespace App\Features\User\AdminUsers\Contracts;

use App\Features\User\Users\DTO\UsersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AdminUsersRepositoryInterface
{
    public function findAll(UsersFiltersDTO $usersFiltersDTO): LengthAwarePaginator|Collection;
    public function findByUserId(string $userId): ?object;
    public function create(string $userId): object;
}
