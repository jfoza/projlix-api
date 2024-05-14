<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Shared\Enums\RulesEnum;

class UpdateProjectBusiness extends Business implements UpdateProjectBusinessInterface
{
    private ProjectDTO $projectDTO;

    public function __construct(
        private readonly ProjectsRepositoryInterface $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(ProjectDTO $projectDTO): object
    {
        $this->projectDTO = $projectDTO;

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_UPDATE->value),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_UPDATE->value)
                => $this->updateByAdminMasterAndProjectManager(),

            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_UPDATE->value) => $this->updateByTeamLeader(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMasterAndProjectManager(): object
    {
        $this->handleValidations();

        return $this->projectsRepository->save($this->projectDTO);
    }

    /**
     * @throws AppException
     */
    private function updateByTeamLeader(): object
    {
        $this->handleValidations();

        $this->canAccessProjects([$this->projectDTO->id]);

        return $this->projectsRepository->save($this->projectDTO);
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        ProjectsValidations::projectExists(
            $this->projectDTO->id,
            $this->projectsRepository
        );

        ProjectsValidations::projectExistsByNameInUpdate(
            $this->projectDTO->id,
            $this->projectDTO->name,
            $this->projectsRepository
        );
    }
}
