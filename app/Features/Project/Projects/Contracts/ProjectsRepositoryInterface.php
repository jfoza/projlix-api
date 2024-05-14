<?php

namespace App\Features\Project\Projects\Contracts;

use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\DTO\ProjectsFiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProjectsRepositoryInterface
{
    public function findAll(ProjectsFiltersDTO $projectsFiltersDTO): Collection|LengthAwarePaginator;
    public function findAllWithoutFilters(): Collection;
    public function findById(string $id): ?object;
    public function findByIds(array $ids): Collection;
    public function findByName(string $name): ?object;
    public function create(ProjectDTO $projectDTO): object;
    public function saveTeamUsers(string $projectId, array $teamUsers): void;
    public function save(ProjectDTO $projectDTO): object;
    public function remove(string $id): void;
}
