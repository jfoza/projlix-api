<?php

namespace App\Features\Project\Projects\Contracts;


use App\Features\Project\Projects\DTO\ProjectsFiltersDTO;

interface FindAllProjectsBusinessInterface
{
    public function handle(ProjectsFiltersDTO $projectsFiltersDTO);
}
