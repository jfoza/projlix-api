<?php

namespace App\Features\General\Positions\Contracts;

use App\Features\General\Positions\DTO\PositionsFiltersDTO;

interface FindAllPositionsBusinessInterface
{
    public function execute(PositionsFiltersDTO $positionsFiltersDTO);
}
