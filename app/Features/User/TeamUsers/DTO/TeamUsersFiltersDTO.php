<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\DTO;

use App\Features\Base\DTO\FiltersDTO;

class TeamUsersFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?string $email;
    public ?string $profileId;
    public ?bool   $active;
    public ?array $projectsId;
    public ?array $profilesUniqueName;
}
