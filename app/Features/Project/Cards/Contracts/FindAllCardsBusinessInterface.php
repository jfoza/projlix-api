<?php

namespace App\Features\Project\Cards\Contracts;

use App\Features\Project\Cards\DTO\CardFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllCardsBusinessInterface
{
    public function handle(CardFiltersDTO $cardsFiltersDTO): LengthAwarePaginator|Collection;
}
