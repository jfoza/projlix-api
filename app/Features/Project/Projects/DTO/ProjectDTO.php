<?php

namespace App\Features\Project\Projects\DTO;

class ProjectDTO
{
    public ?string $id;
    public string $name;
    public string $uniqueName;
    public ?string $iconId;
    public ?string $tagId;
    public ?string $teamUserId;
    public ?string $description;
    public ?bool $active;
    public ?array $teamUsers;
    public ?array $tags;
}
