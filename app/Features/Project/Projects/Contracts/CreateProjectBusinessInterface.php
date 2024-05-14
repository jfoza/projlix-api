<?php

namespace App\Features\Project\Projects\Contracts;

use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Responses\SavedProjectsResponse;

interface CreateProjectBusinessInterface
{
    public function handle(ProjectDTO $projectDTO): SavedProjectsResponse;
}
