<?php

namespace App\Features\Project\Cards\Contracts;

use App\Features\Project\Cards\DTO\CardDTO;

interface CreateCardBusinessInterface
{
    public function handle(CardDTO $cardsDto): object;
}
