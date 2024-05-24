<?php

namespace App\Features\User\TeamUsers\Contracts;

use App\Features\User\TeamUsers\DTO\TeamUsersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllTeamUsersBusinessInterface
{
    public function handle(TeamUsersFiltersDTO $teamUsersFiltersDTO): LengthAwarePaginator|Collection;
}
