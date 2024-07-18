<?php

namespace App\Features\Project\Sections\Contracts;

use App\Features\Project\Sections\DTO\SectionsDTO;
use App\Features\Project\Sections\DTO\SectionsFiltersDTO;
use Illuminate\Support\Collection;

interface SectionsRepositoryInterface
{
    public function findAll(SectionsFiltersDTO $sectionsFiltersDTO): Collection;
    public function findById(string $id): ?object;
    public function create(SectionsDTO $sectionsDTO): object;
    public function save(SectionsDTO $sectionsDTO): object;
    public function remove(string $id): void;
    public function reorderSection(string $sectionId, int $newOrder, string $projectId): void;
}
