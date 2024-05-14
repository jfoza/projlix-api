<?php
declare(strict_types=1);

namespace Tests\Unit\Mocks;

use App\Features\Project\Projects\Models\Project;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;

class ProjectMocks
{
    public static function getProject1(): object
    {
        return (object) ([
            Project::ID => Uuid::uuid4Generate(),
            Project::NAME => 'Project 1',
        ]);
    }

    public static function getProject2(): object
    {
        return (object) ([
            Project::ID => Uuid::uuid4Generate(),
            Project::NAME => 'Project 1',
        ]);
    }

    public static function getProject3(): object
    {
        return (object) ([
            Project::ID => Uuid::uuid4Generate(),
            Project::NAME => 'Project 1',
        ]);
    }

    public static function getAllProjects(): Collection
    {
        return Collection::make([
            self::getProject1(),
            self::getProject2(),
            self::getProject3(),
        ]);
    }
}
