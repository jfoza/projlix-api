<?php
declare(strict_types=1);

namespace App\Features\User\Users\DTO;

use App\Features\Base\DTO\FiltersDTO;

class UsersFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?string $email;
    public ?string $profile;
    public ?array  $profileUniqueName;
    public ?array  $projectsId;
    public ?bool   $active;
}
