<?php

namespace App\Features\User\TeamUsers\Contracts;

use App\Features\User\TeamUsers\DTO\TeamUsersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TeamUsersRepositoryInterface
{
    public function findAll(TeamUsersFiltersDTO $teamUsersFiltersDTO): LengthAwarePaginator|Collection;
    public function findByUserId(string $userId): ?object;
    public function findByTeamUserId(string $teamUserId): ?object;
    public function findByTeamUsersIds(array $ids): Collection;
    public function create(string $userId): object;
    public function saveProjects(string $teamUserId, array $projectsId): void;
}
