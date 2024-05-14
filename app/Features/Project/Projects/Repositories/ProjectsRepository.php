<?php

namespace App\Features\Project\Projects\Repositories;

use App\Features\Base\Traits\BuilderTrait;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\DTO\ProjectsFiltersDTO;
use App\Features\Project\Projects\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProjectsRepository implements ProjectsRepositoryInterface
{
    use BuilderTrait;

    public function __construct(private readonly Project $project) {}

    public function findAll(ProjectsFiltersDTO $projectsFiltersDTO): Collection|LengthAwarePaginator
    {
        $builder = Project::with(['teamUsers.user'])
            ->when(
                isset($projectsFiltersDTO->title),
                fn($q) => $q->where(Project::NAME, $projectsFiltersDTO->name)
            )
            ->when(
                isset($projectsFiltersDTO->projectsId),
                fn($q) => $q->whereIn(Project::ID, $projectsFiltersDTO->projectsId)
            )
            ->orderBy(
                $projectsFiltersDTO->paginationOrder->defineCustomColumnName(Project::CREATED_AT),
                $projectsFiltersDTO->paginationOrder->getColumnOrder(),
            );

        return $this->paginateOrGet($builder, $projectsFiltersDTO->paginationOrder);
    }

    public function findAllWithoutFilters(): Collection
    {
        return collect(Project::get());
    }

    public function findById(string $id): ?object
    {
        return Project::where(Project::ID, $id)->first();
    }

    public function findByIds(array $ids): Collection
    {
        return collect(
            Project::whereIn(Project::ID, $ids)->get()
        );
    }

    public function findByName(string $name): ?object
    {
        return Project::where(Project::NAME, $name)->first();
    }

    public function create(ProjectDTO $projectDTO): object
    {
        return $this->project->create([
            Project::NAME        => $projectDTO->name,
            Project::DESCRIPTION => $projectDTO->description,
            Project::ACTIVE      => true,
        ]);
    }

    public function save(ProjectDTO $projectDTO): object
    {
        $updated = [
            Project::ID          => $projectDTO->id,
            Project::NAME        => $projectDTO->name,
            Project::DESCRIPTION => $projectDTO->description,
        ];

        $this->project->where(Project::ID, $projectDTO->id)->update($updated);

        return (object) $updated;
    }

    public function saveTeamUsers(string $projectId, array $teamUsers): void
    {
        Project::find($projectId)->teamUsers()->sync($teamUsers);
    }

    public function remove(string $id): void
    {
        Project::where(Project::ID, $id)->delete();
    }
}
