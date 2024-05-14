<?php

namespace App\Features\Project\Cards\Contracts;

use App\Features\Project\Cards\DTO\CardDTO;

interface UpdateCardBusinessInterface
{
    public function handle(CardDTO $cardsDto): object;
}
