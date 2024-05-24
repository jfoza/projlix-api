<?php
declare(strict_types=1);

namespace App\Features\User\Profiles\DTO;

use App\Features\Base\DTO\FiltersDTO;

class ProfilesFiltersDTO extends FiltersDTO
{
    public ?array $profileUniqueName;
    public ?string $profileType;
}
