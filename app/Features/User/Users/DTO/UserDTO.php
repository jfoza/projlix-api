<?php
declare(strict_types=1);

namespace App\Features\User\Users\DTO;

class UserDTO
{
    public ?string $id;
    public ?string $personId = null;
    public ?string $name;
    public ?string $shortName;
    public ?string $email;
    public ?string $password;
    public ?string $passwordConfirmation;
    public ?string $profileId;
    public ?array $projectsId;
}
