<?php

namespace App\Features\General\Positions\Repositories;

use App\Features\Base\Traits\BuilderTrait;
use App\Features\General\Positions\Contracts\PositionsRepositoryInterface;
use App\Features\General\Positions\DTO\PositionsDTO;
use App\Features\General\Positions\DTO\PositionsFiltersDTO;
use App\Features\General\Positions\Models\Position;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PositionsRepository implements PositionsRepositoryInterface
{
    use BuilderTrait;

    public function __construct(private readonly Position $position) {}

    public function findAll(PositionsFiltersDTO $positionsFiltersDTO): Collection|LengthAwarePaginator
    {
        $builder = Position::with([])
            ->when(
                isset($positionsFiltersDTO->title),
                fn($q) => $q->where(Position::NAME, $positionsFiltersDTO->name)
            )
            ->orderBy(
                $positionsFiltersDTO->paginationOrder->defineCustomColumnName(Position::CREATED_AT),
                $positionsFiltersDTO->paginationOrder->getColumnOrder(),
            );

        return $this->paginateOrGet($builder, $positionsFiltersDTO->paginationOrder);
    }

    public function findById(string $id): ?object
    {
        return Position::where(Position::ID, $id)->first();
    }

    public function findByName(string $name): ?object
    {
        return Position::where(Position::NAME, $name)->first();
    }

    public function create(PositionsDTO $positionsDTO): object
    {
        return $this->position->create([
            Position::NAME        => $positionsDTO->name,
            Position::DESCRIPTION => $positionsDTO->description,
            Position::ACTIVE      => $positionsDTO->active,
        ]);
    }

    public function save(PositionsDTO $positionsDTO): object
    {
        $updated = [
            Position::ID          => $positionsDTO->id,
            Position::NAME        => $positionsDTO->name,
            Position::DESCRIPTION => $positionsDTO->description,
            Position::ACTIVE      => $positionsDTO->active,
        ];

        $this->position->where(Position::ID, $positionsDTO->id)->update($updated);

        return (object) $updated;
    }

    public function remove(string $id): void
    {
        $this->position->where(Position::ID, $id)->delete();
    }
}
