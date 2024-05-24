<?php
declare(strict_types=1);

namespace App\Features\User\AdminUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\User\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\User\AdminUsers\Contracts\UpdateAdminUserBusinessInterface;
use App\Features\User\AdminUsers\Responses\SavedAdminUserResponse;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\DTO\UserDTO;
use App\Features\User\Users\Validations\UsersValidations;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Symfony\Component\HttpFoundation\Response;

class UpdateAdminUserBusiness extends Business implements UpdateAdminUserBusinessInterface
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(UserDTO $userDTO): SavedAdminUserResponse
    {
        $this->getPolicy()->havePermission(RulesEnum::ADMIN_USERS_UPDATE->value);

        if(!$adminUser = $this->adminUsersRepository->findByUserId($userDTO->id))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        UsersValidations::emailAlreadyExistsInUpdate(
            $userDTO->id,
            $userDTO->email,
            $this->usersRepository
        );

        Transaction::beginTransaction();

        try
        {
            $userDTO->shortName = strtoupper(substr($userDTO->name, 0, 2));

            $userUpdated = $this->usersRepository->update($userDTO);

            Transaction::commit();

            return SavedAdminUserResponse::setUp(
                $userUpdated->id,
                $userUpdated->name,
                $userUpdated->email,
                $adminUser->active,
                'Admin Master'
            );
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
