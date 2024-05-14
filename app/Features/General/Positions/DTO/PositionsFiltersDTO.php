<?php

namespace App\Features\General\Positions\DTO;

use App\Features\Base\DTO\FiltersDTO;

class PositionsFiltersDTO extends FiltersDTO
{
    public ?string $id;
    public ?string $name;
    public ?string $userId;
}
