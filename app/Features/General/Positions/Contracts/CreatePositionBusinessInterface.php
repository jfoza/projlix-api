<?php

namespace App\Features\General\Positions\Contracts;

use App\Features\General\Positions\DTO\PositionsDTO;

interface CreatePositionBusinessInterface
{
    public function execute(PositionsDTO $positionsDTO);
}
