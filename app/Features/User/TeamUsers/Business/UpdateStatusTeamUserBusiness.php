<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\User\TeamUsers\Contracts\UpdateStatusTeamUserBusinessInterface;
use App\Features\User\Users\Contracts\UserUpdateStatusServiceInterface;
use App\Shared\Enums\RulesEnum;

class UpdateStatusTeamUserBusiness extends Business implements UpdateStatusTeamUserBusinessInterface
{
    public function __construct(
        private readonly UserUpdateStatusServiceInterface $userUpdateStatusService
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $userId): object
    {
        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::TEAM_USERS_ADMIN_MASTER_UPDATE->value),
            $policy->haveRule(RulesEnum::TEAM_USERS_PROJECT_MANAGER_UPDATE->value) => $this->updateStatusUser($userId),

            default => $policy->dispatchForbiddenError(),
        };
    }

    private function updateStatusUser(string $userId): object
    {
        return $this->userUpdateStatusService->execute($userId);
    }
}
