<?php

namespace App\Features\Project\Projects\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\Project\Projects\Business\AddProjectTeamUserBusiness;
use App\Features\Project\Projects\Business\CreateProjectBusiness;
use App\Features\Project\Projects\Business\FindAllProjectsBusiness;
use App\Features\Project\Projects\Business\RemoveProjectBusiness;
use App\Features\Project\Projects\Business\RemoveProjectTagBusiness;
use App\Features\Project\Projects\Business\RemoveProjectTeamUserBusiness;
use App\Features\Project\Projects\Business\ShowProjectBusiness;
use App\Features\Project\Projects\Business\UpdateProjectIconBusiness;
use App\Features\Project\Projects\Business\UpdateProjectInfoBusiness;
use App\Features\Project\Projects\Business\AddProjectTagBusiness;
use App\Features\Project\Projects\Contracts\AddProjectTeamUserBusinessInterface;
use App\Features\Project\Projects\Contracts\CreateProjectBusinessInterface;
use App\Features\Project\Projects\Contracts\FindAllProjectsBusinessInterface;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\RemoveProjectBusinessInterface;
use App\Features\Project\Projects\Contracts\RemoveProjectTagBusinessInterface;
use App\Features\Project\Projects\Contracts\RemoveProjectTeamUserBusinessInterface;
use App\Features\Project\Projects\Contracts\ShowProjectBusinessInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectIconBusinessInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectInfoBusinessInterface;
use App\Features\Project\Projects\Contracts\AddProjectTagBusinessInterface;
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
            UpdateProjectInfoBusinessInterface::class,
            UpdateProjectInfoBusiness::class
        );

        $this->bind(
            UpdateProjectIconBusinessInterface::class,
            UpdateProjectIconBusiness::class
        );

        $this->bind(
            AddProjectTagBusinessInterface::class,
            AddProjectTagBusiness::class
        );

        $this->bind(
            AddProjectTeamUserBusinessInterface::class,
            AddProjectTeamUserBusiness::class
        );

        $this->bind(
            RemoveProjectTagBusinessInterface::class,
            RemoveProjectTagBusiness::class
        );

        $this->bind(
            RemoveProjectTeamUserBusinessInterface::class,
            RemoveProjectTeamUserBusiness::class
        );

        $this->bind(
            RemoveProjectBusinessInterface::class,
            RemoveProjectBusiness::class
        );
    }
}
