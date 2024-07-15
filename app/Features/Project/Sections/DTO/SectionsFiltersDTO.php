<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\DTO;

use App\Features\Base\DTO\FiltersDTO;

class SectionsFiltersDTO extends FiltersDTO
{
    public ?string $projectId;
    public ?string $projectUniqueName;
}
