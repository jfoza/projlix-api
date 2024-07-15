<?php
declare(strict_types=1);

namespace App\Features\General\Tags\Contracts;

use App\Features\General\Tags\DTO\TagsDTO;
use App\Features\General\Tags\DTO\TagsFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TagsRepositoryInterface
{
    public function findAll(TagsFiltersDTO $tagsFiltersDTO): LengthAwarePaginator|Collection;
    public function findById(string $id): ?object;
    public function findByIds(array $ids): Collection;
    public function create(TagsDTO $tagsDTO): object;
    public function save(TagsDTO $tagsDTO): object;
    public function saveStatus(string $id, bool $status): void;
    public function remove(string $id): void;
}
