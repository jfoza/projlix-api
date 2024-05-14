<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\User\TeamUsers\Contracts\FindAllTeamUsersBusinessInterface;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\Users\DTO\UsersFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllTeamUsersBusiness extends Business implements FindAllTeamUsersBusinessInterface
{
    private UsersFiltersDTO $usersFiltersDTO;

    public function __construct(
        private readonly TeamUsersRepositoryInterface $teamUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(UsersFiltersDTO $usersFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->usersFiltersDTO = $usersFiltersDTO;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::TEAM_USERS_ADMIN_MASTER_VIEW->value),
            $policy->haveRule(RulesEnum::TEAM_USERS_PROJECT_MANAGER_VIEW->value)
                => $this->findByAdminMasterAndProjectManager(),

            $policy->haveRule(RulesEnum::TEAM_USERS_TEAM_LEADER_VIEW->value),
            $policy->haveRule(RulesEnum::TEAM_USERS_PROJECT_MEMBER_VIEW->value)
                => $this->findByTeamLeaderAndProjectMember(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    private function findByAdminMasterAndProjectManager(): LengthAwarePaginator|Collection
    {
        return $this->teamUsersRepository->findAll($this->usersFiltersDTO);
    }

    private function findByTeamLeaderAndProjectMember(): LengthAwarePaginator|Collection
    {
        $this->usersFiltersDTO->projectsId = $this->getTeamUserProjectsId();

        return $this->teamUsersRepository->findAll($this->usersFiltersDTO);
    }
}
