<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Repositories;

use App\Features\Project\Projects\Models\Project;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\DTO\SectionsDTO;
use App\Features\Project\Sections\DTO\SectionsFiltersDTO;
use App\Features\Project\Sections\Models\Section;
use Illuminate\Support\Collection;

class SectionsRepository implements SectionsRepositoryInterface
{
    public function __construct(
        private readonly Section $section,
    ) {}

    public function findAll(SectionsFiltersDTO $sectionsFiltersDTO): Collection
    {
        $relations = [
            'project',
            'color',
            'icon',
            'cards.tag',
            'cards.user'
        ];

        return collect(
            Section::with($relations)
                ->when(
                    isset($sectionsFiltersDTO->projectId),
                    fn($q) => $q->where(Section::PROJECT_ID, $sectionsFiltersDTO->projectId)
                )
                ->when(
                    isset($sectionsFiltersDTO->projectUniqueName),
                    fn($q) => $q->whereRelation(
                        'project',
                        Project::UNIQUE_NAME,
                        $sectionsFiltersDTO->projectUniqueName
                    )
                )
                ->get()
        );
    }

    public function findById(string $id): ?object
    {
        return Section::where(Section::ID, $id)->first();
    }

    public function create(SectionsDTO $sectionsDTO): object
    {
        return $this->section->create([
            Section::PROJECT_ID => $sectionsDTO->projectId,
            Section::COLOR_ID   => $sectionsDTO->colorId,
            Section::ICON_ID    => $sectionsDTO->iconId,
            Section::NAME       => $sectionsDTO->name,
        ]);
    }

    public function save(SectionsDTO $sectionsDTO): object
    {
        $updated = [
            Section::ID       => $sectionsDTO->id,
            Section::COLOR_ID => $sectionsDTO->colorId,
            Section::ICON_ID  => $sectionsDTO->iconId,
            Section::NAME     => $sectionsDTO->name,
        ];

        $this->section->where(Section::ID, $sectionsDTO->id)->update($updated);

        return (object) $updated;
    }

    public function remove(string $id): void
    {
        $this->section->where(Section::ID, $id)->delete();
    }
}
