<?php

namespace App\Features\Project\Projects\DTO;

use App\Features\Base\DTO\FiltersDTO;

class ProjectsFiltersDTO extends FiltersDTO
{
    public ?array $projectsId;
    public ?string $name;
    public ?string $userId;
}
