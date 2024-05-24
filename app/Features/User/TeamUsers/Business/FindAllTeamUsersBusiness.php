<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Features\User\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\User\TeamUsers\Contracts\FindAllTeamUsersBusinessInterface;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\DTO\TeamUsersFiltersDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllTeamUsersBusiness extends Business implements FindAllTeamUsersBusinessInterface
{
    private TeamUsersFiltersDTO $teamUsersFiltersDTO;

    public function __construct(
        private readonly TeamUsersRepositoryInterface $teamUsersRepository,
        private readonly ProjectsRepositoryInterface $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(TeamUsersFiltersDTO $teamUsersFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->teamUsersFiltersDTO = $teamUsersFiltersDTO;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::TEAM_USERS_ADMIN_MASTER_VIEW->value)    => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::TEAM_USERS_PROJECT_MANAGER_VIEW->value) => $this->findByProjectManager(),
            $policy->haveRule(RulesEnum::TEAM_USERS_TEAM_LEADER_VIEW->value)     => $this->findByTeamLeader(),
            $policy->haveRule(RulesEnum::TEAM_USERS_PROJECT_MEMBER_VIEW->value)  => $this->findByProjectMember(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    private function findByAdminMaster(): LengthAwarePaginator|Collection
    {
        $this->teamUsersFiltersDTO->profilesUniqueName = ProfileUniqueNameEnum::PROFILES_BY_ADMIN_MASTER;

        return $this->teamUsersRepository->findAll($this->teamUsersFiltersDTO);
    }

    private function findByProjectManager(): LengthAwarePaginator|Collection
    {
        $this->teamUsersFiltersDTO->profilesUniqueName = ProfileUniqueNameEnum::PROFILES_BY_PROJECT_MANAGER;

        return $this->teamUsersRepository->findAll($this->teamUsersFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findByTeamLeader(): LengthAwarePaginator|Collection
    {
        $this->handleValidateAccessProjects();

        $this->teamUsersFiltersDTO->profilesUniqueName = ProfileUniqueNameEnum::PROFILES_BY_TEAM_LEADER;

        return $this->teamUsersRepository->findAll($this->teamUsersFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findByProjectMember(): LengthAwarePaginator|Collection
    {
        $this->handleValidateAccessProjects();

        $this->teamUsersFiltersDTO->profilesUniqueName = ProfileUniqueNameEnum::PROFILES_BY_PROJECT_MEMBER;

        return $this->teamUsersRepository->findAll($this->teamUsersFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function handleValidateAccessProjects(): void
    {
        if(!empty($this->teamUsersFiltersDTO->projectsId))
        {
            ProjectsValidations::projectsExists(
                $this->teamUsersFiltersDTO->projectsId,
                $this->projectsRepository
            );

            $this->canAccessEachProject(
                $this->teamUsersFiltersDTO->projectsId,
                MessagesEnum::PROJECT_NOT_ALLOWED_IN_TEAM_USERS->value
            );
        }
        else
        {
            $this->teamUsersFiltersDTO->projectsId = $this->getTeamUserProjectsId();
        }
    }
}
