<?php

namespace App\Features\User\AdminUsers\Contracts;

use App\Features\User\Users\DTO\UsersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllAdminUsersBusinessInterface
{
    public function handle(UsersFiltersDTO $usersFiltersDTO): LengthAwarePaginator|Collection;
}
