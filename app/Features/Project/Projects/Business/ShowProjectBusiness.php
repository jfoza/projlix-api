<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\ShowProjectBusinessInterface;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Features\User\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\RulesEnum;

class ShowProjectBusiness extends Business implements ShowProjectBusinessInterface
{
    private string $id;
    private ?object $project;

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
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_VIEW->value),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_VIEW->value)
                => $this->findByAdminMasterAndProjectManager(),

            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_VIEW->value) => $this->findByTeamLeader(),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MEMBER_VIEW->value) => $this->findByProjectMember(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function findByAdminMasterAndProjectManager(): object
    {
        $this->project = ProjectsValidations::projectExists(
            $this->id,
            $this->projectsRepository
        );

        $this->project->team_users = $this->project->teamUsers->map(fn($teamUser) => $teamUser->can = true);

        return $this->project;
    }

    /**
     * @throws AppException
     */
    private function findByTeamLeader(): object
    {
        $this->project = ProjectsValidations::projectExists(
            $this->id,
            $this->projectsRepository
        );

        $this->canAccessProjects([$this->id]);

        $this->setTeamUsers(ProfileUniqueNameEnum::PROFILES_BY_TEAM_LEADER);

        return $this->project;
    }

    /**
     * @throws AppException
     */
    private function findByProjectMember(): object
    {
        $this->project = ProjectsValidations::projectExists(
            $this->id,
            $this->projectsRepository
        );

        $this->canAccessProjects([$this->id]);

        $this->setTeamUsers(ProfileUniqueNameEnum::PROFILES_BY_PROJECT_MEMBER);

        return $this->project;
    }

    private function setTeamUsers(array $profiles): void
    {
        $this->project->team_users = $this->project->teamUsers->map(function ($teamUser) use($profiles) {
            $teamUser->can = false;

            if(in_array($teamUser->profile_unique_name, $profiles))
            {
                $teamUser->can = true;
            }
        });
    }
}
