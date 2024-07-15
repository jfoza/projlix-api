<?php

namespace App\Features\Project\Projects\Contracts;

interface ProjectUpdateAccessServiceInterface
{
    public function execute(string $projectId): object;
}
