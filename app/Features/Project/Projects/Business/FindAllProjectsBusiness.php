<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\FindAllProjectsBusinessInterface;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\DTO\ProjectsFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllProjectsBusiness extends Business implements FindAllProjectsBusinessInterface
{
    private ProjectsFiltersDTO $projectsFiltersDTO;

    public function __construct(
        private readonly ProjectsRepositoryInterface $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(ProjectsFiltersDTO $projectsFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->projectsFiltersDTO = $projectsFiltersDTO;

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_VIEW->value),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_VIEW->value)
                => $this->findByAdminMasterAndProjectManager(),

            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_VIEW->value),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MEMBER_VIEW->value)
                => $this->findByTeamLeaderAndProjectMember(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    private function findByAdminMasterAndProjectManager(): LengthAwarePaginator|Collection
    {
        return $this->projectsRepository->findAll($this->projectsFiltersDTO);
    }

    private function findByTeamLeaderAndProjectMember(): LengthAwarePaginator|Collection
    {
        $this->projectsFiltersDTO->projectsId = $this->getTeamUserProjectsId();

        return $this->projectsRepository->findAll($this->projectsFiltersDTO);
    }
}
