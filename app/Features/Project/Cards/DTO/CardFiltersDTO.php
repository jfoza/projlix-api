<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\DTO;

use App\Features\Base\DTO\FiltersDTO;

class CardFiltersDTO extends FiltersDTO
{
    public ?array $projectsId;
    public ?string $code;
    public ?string $sectionId;
    public ?string $initialDate;
    public ?string $finalDate;
    public ?string $responsibleId;
    public ?array  $tagsId;
}
