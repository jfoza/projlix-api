<?php

namespace App\Features\User\TeamUsers\Contracts;

use App\Features\User\Users\DTO\UsersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllTeamUsersBusinessInterface
{
    public function handle(UsersFiltersDTO $usersFiltersDTO): LengthAwarePaginator|Collection;
}
