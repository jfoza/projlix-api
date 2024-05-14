<?php

namespace App\Features\Project\Projects\DTO;

class ProjectDTO
{
    public ?string $id;
    public string $name;
    public ?string $description;
    public ?bool $active;
    public ?array $teamUsers;
}
