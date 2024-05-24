<?php
declare(strict_types=1);

namespace App\Features\User\AdminUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\User\AdminUsers\Contracts\UpdateStatusAdminUserBusinessInterface;
use App\Features\User\Users\Contracts\UserUpdateStatusServiceInterface;
use App\Shared\Enums\RulesEnum;

class UpdateStatusAdminUserBusiness extends Business implements UpdateStatusAdminUserBusinessInterface
{
    public function __construct(
        private readonly UserUpdateStatusServiceInterface $userUpdateStatusService
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $userId): object
    {
        $this->getPolicy()->havePermission(RulesEnum::ADMIN_USERS_UPDATE->value);

        return $this->userUpdateStatusService->execute($userId);
    }
}
