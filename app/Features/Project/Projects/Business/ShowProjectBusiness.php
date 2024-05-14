<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\ShowProjectBusinessInterface;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Shared\Enums\RulesEnum;

class ShowProjectBusiness extends Business implements ShowProjectBusinessInterface
{
    private string $id;

    public function __construct(
        private readonly ProjectsRepositoryInterface $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): object
    {
        $this->id = $id;

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_VIEW->value)    => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_VIEW->value) => $this->findByProjectManager(),

            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_VIEW->value),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MEMBER_VIEW->value)
                => $this->findByTeamLeaderAndProjectMember(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function findByAdminMaster(): object
    {
        return ProjectsValidations::projectExists(
            $this->id,
            $this->projectsRepository
        );
    }

    /**
     * @throws AppException
     */
    private function findByProjectManager(): object
    {
        return ProjectsValidations::projectExists(
            $this->id,
            $this->projectsRepository
        );
    }

    /**
     * @throws AppException
     */
    private function findByTeamLeaderAndProjectMember(): object
    {
        $project = ProjectsValidations::projectExists(
            $this->id,
            $this->projectsRepository
        );

        $this->canAccessProjects([$this->id]);

        return $project;
    }
}
