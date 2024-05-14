<?php

namespace App\Features\Project\Cards\Contracts;

use App\Features\Project\Cards\DTO\CardDTO;
use App\Features\Project\Cards\DTO\CardFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CardsRepositoryInterface
{
    public function findAll(CardFiltersDTO $cardsFiltersDTO): LengthAwarePaginator|Collection;
    public function findById(string $id): ?object;
    public function create(CardDTO $cardsDto): object;
    public function save(CardDTO $cardsDto): object;
    public function remove(string $id): void;
}
