<?php
declare(strict_types=1);

namespace App\Features\Project\Projects\Responses;

use Illuminate\Support\Collection;

class SavedProjectsResponse
{
    public string $id;
    public string $name;
    public string $description;
    public Collection $teamUsers;

    public function __construct(
        string $id,
        string $name,
        string $description,
        Collection $teamUsers,
    )
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->description = $description;
        $this->teamUsers   = $teamUsers;
    }

    public static function setUp(
        string $id,
        string $name,
        string $description,
        Collection $teamUsers,
    ): SavedProjectsResponse
    {
        return new self($id, $name, $description, $teamUsers);
    }
}
