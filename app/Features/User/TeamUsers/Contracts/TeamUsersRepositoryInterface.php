<?php

namespace App\Features\User\TeamUsers\Contracts;

use App\Features\User\Users\DTO\UsersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TeamUsersRepositoryInterface
{
    public function findAll(UsersFiltersDTO $usersFiltersDTO): LengthAwarePaginator|Collection;
    public function findByUserId(string $userId): ?object;
    public function findByTeamUsersIds(array $ids): Collection;
    public function create(string $userId): object;
    public function saveProjects(string $teamUserId, array $projectsId): void;
}
