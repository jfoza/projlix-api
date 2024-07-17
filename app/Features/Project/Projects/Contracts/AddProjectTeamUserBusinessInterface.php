<?php

namespace App\Features\Project\Projects\Contracts;

use App\Features\Project\Projects\DTO\ProjectDTO;

interface AddProjectTeamUserBusinessInterface
{
    public function handle(ProjectDTO $projectDTO): void;
}
