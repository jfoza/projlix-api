<?php

namespace App\Features\Project\Projects\Services;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\ProjectUpdateAccessServiceInterface;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Shared\Enums\RulesEnum;

class ProjectUpdateAccessService extends Business implements ProjectUpdateAccessServiceInterface
{
    private string $projectId;
    private ?object $project;

    public function __construct(
        private readonly ProjectsRepositoryInterface $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $projectId): object
    {
        $this->projectId = $projectId;

        $policy = $this->getPolicy();

        match (true)
        {
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_UPDATE->value),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_UPDATE->value)
                => $this->updateByAdminMasterAndProjectManager(),

            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_UPDATE->value)
                => $this->updateByTeamLeader(),

            default => $policy->dispatchForbiddenError(),
        };

        return $this->project;
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMasterAndProjectManager(): void
    {
        $this->project = ProjectsValidations::projectExists(
            $this->projectId,
            $this->projectsRepository
        );
    }

    /**
     * @throws AppException
     */
    private function updateByTeamLeader(): void
    {
        $this->project = ProjectsValidations::projectExists(
            $this->projectId,
            $this->projectsRepository
        );

        $this->canAccessProjects([$this->projectId]);
    }
}
