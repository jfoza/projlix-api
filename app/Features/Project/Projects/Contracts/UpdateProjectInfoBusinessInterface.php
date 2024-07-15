<?php

namespace App\Features\Project\Projects\Contracts;

use App\Features\Project\Projects\DTO\ProjectDTO;

interface UpdateProjectInfoBusinessInterface
{
    public function handle(ProjectDTO $projectDTO): object;
}
