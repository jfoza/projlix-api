<?php
declare(strict_types=1);

namespace App\Features\General\Tags\DTO;

use App\Features\Base\DTO\FiltersDTO;

class TagsFiltersDTO extends FiltersDTO
{
    public ?string $name;
}
