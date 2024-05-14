<?php

namespace App\Features\General\Positions\Contracts;

use App\Features\General\Positions\DTO\PositionsDTO;
use App\Features\General\Positions\DTO\PositionsFiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PositionsRepositoryInterface
{
    public function findAll(PositionsFiltersDTO $positionsFiltersDTO): Collection|LengthAwarePaginator;
    public function findById(string $id): ?object;
    public function findByName(string $name): ?object;
    public function create(PositionsDTO $positionsDTO): object;
    public function save(PositionsDTO $positionsDTO): object;
    public function remove(string $id): void;
}
