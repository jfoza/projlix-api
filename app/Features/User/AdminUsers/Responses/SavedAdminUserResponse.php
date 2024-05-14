<?php
declare(strict_types=1);

namespace App\Features\User\AdminUsers\Responses;

class SavedAdminUserResponse
{
    public ?string $id;
    public string $name;
    public string $email;
    public ?bool $active;
    public ?string $profile;

    public function __construct(
        string $id,
        string $name,
        string $email,
        ?bool $active,
        ?string $profile
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->active = $active;
        $this->profile = $profile;
    }

    public static function setUp(
        string $id,
        string $name,
        string $email,
        ?bool $active,
        ?string $profile
    ): SavedAdminUserResponse
    {
        return new self($id, $name, $email, $active, $profile);
    }
}
