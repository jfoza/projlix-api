<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Responses;

use Illuminate\Support\Collection;

class SavedTeamUserResponse
{
    public ?string $id;
    public string $name;
    public string $email;
    public ?bool $active;
    public ?string $profile;
    public Collection $projects;

    public function __construct(
        string $id,
        string $name,
        string $email,
        ?bool $active,
        ?string $profile,
        Collection $projects
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->active = $active;
        $this->profile = $profile;
        $this->projects = $projects;
    }

    public static function setUp(
        string $id,
        string $name,
        string $email,
        ?bool $active,
        ?string $profile,
        Collection $projects
    ): SavedTeamUserResponse
    {
        return new self($id, $name, $email, $active, $profile, $projects);
    }
}
