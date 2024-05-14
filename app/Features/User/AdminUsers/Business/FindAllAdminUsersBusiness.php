<?php
declare(strict_types=1);

namespace App\Features\User\AdminUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\User\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\User\AdminUsers\Contracts\FindAllAdminUsersBusinessInterface;
use App\Features\User\Users\DTO\UsersFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllAdminUsersBusiness extends Business implements FindAllAdminUsersBusinessInterface
{
    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(UsersFiltersDTO $usersFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value);

        return $this->adminUsersRepository->findAll($usersFiltersDTO);
    }
}
