<?php
declare(strict_types=1);

namespace App\Features\User\AdminUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\User\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\User\AdminUsers\Contracts\ShowAdminUserBusinessInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Symfony\Component\HttpFoundation\Response;

class ShowAdminUserBusiness extends Business implements ShowAdminUserBusinessInterface
{
    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $userId): object
    {
        $this->getPolicy()->havePermission(RulesEnum::ADMIN_USERS_VIEW->value);

        if(!$adminUser = $this->adminUsersRepository->findByUserId($userId))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $adminUser;
    }
}
