<?php

namespace App\Features\Project\Projects\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\Project\Projects\Business\CreateProjectBusiness;
use App\Features\Project\Projects\Business\FindAllProjectsBusiness;
use App\Features\Project\Projects\Business\RemoveProjectBusiness;
use App\Features\Project\Projects\Business\ShowProjectBusiness;
use App\Features\Project\Projects\Business\UpdateProjectBusiness;
use App\Features\Project\Projects\Contracts\CreateProjectBusinessInterface;
use App\Features\Project\Projects\Contracts\FindAllProjectsBusinessInterface;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\RemoveProjectBusinessInterface;
use App\Features\Project\Projects\Contracts\ShowProjectBusinessInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectBusinessInterface;
use App\Features\Project\Projects\Repositories\ProjectsRepository;

class ProjectsProviders extends ServiceProvider
{
    public array $bindings = [
        ProjectsRepositoryInterface::class => ProjectsRepository::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->bind(
            FindAllProjectsBusinessInterface::class,
            FindAllProjectsBusiness::class
        );

        $this->bind(
            ShowProjectBusinessInterface::class,
            ShowProjectBusiness::class
        );

        $this->bind(
            CreateProjectBusinessInterface::class,
            CreateProjectBusiness::class
        );

        $this->bind(
            UpdateProjectBusinessInterface::class,
            UpdateProjectBusiness::class
        );

        $this->bind(
            RemoveProjectBusinessInterface::class,
            RemoveProjectBusiness::class
        );
    }
}
