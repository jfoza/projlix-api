<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Models\Project;
use App\Features\User\TeamUsers\Contracts\ShowTeamUserBusinessInterface;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\Validations\TeamUsersValidations;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;

class ShowTeamUserBusiness extends Business implements ShowTeamUserBusinessInterface
{
    private string $userId;

    public function __construct(
        private readonly TeamUsersRepositoryInterface $teamUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $userId): object
    {
        $this->userId = $userId;

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

    /**
     * @throws AppException
     */
    private function findByAdminMasterAndProjectManager(): object
    {
        return TeamUsersValidations::teamUserExistsByUserId(
            $this->userId,
            $this->teamUsersRepository,
        );
    }

    /**
     * @throws AppException
     */
    private function findByTeamLeaderAndProjectMember(): object
    {
        $teamUser = TeamUsersValidations::teamUserExistsByUserId(
            $this->userId,
            $this->teamUsersRepository,
        );

        $projectsId = $teamUser
            ->projects
            ->pluck(Project::ID)
            ->toArray();

        $this->canAccessProjects($projectsId, MessagesEnum::USER_NOT_ALLOWED->value);

        return $teamUser;
    }
}
