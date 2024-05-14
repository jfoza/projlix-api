<?php
declare(strict_types=1);

namespace Tests\Unit\Resources;

use Illuminate\Support\Collection;

class TeamUserMock
{
    public string $id;
    public string $userId;
    public Collection $projects;

    public function __construct(
        string $id,
        string $userId,
        Collection $projects,
    )
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->projects = $projects;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function setProjects(Collection $projects): void
    {
        $this->projects = $projects;
    }
}
