<?php
declare(strict_types=1);

namespace App\Features\User\Users\Services;

use App\Exceptions\AppException;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\Contracts\UserUpdateStatusServiceInterface;
use App\Features\User\Users\Validations\UsersValidations;
use App\Shared\Utils\Transaction;

class UserUpdateStatusService implements UserUpdateStatusServiceInterface
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $userId): object
    {
        $user = UsersValidations::validateUserExistsById(
            $userId,
            $this->usersRepository
        );

        Transaction::beginTransaction();

        try
        {
            $newStatus = !$user->active;

            $this->usersRepository->saveStatus(
                $userId,
                $newStatus
            );

            Transaction::commit();

            return (object) ([
                'id'     => $user->id,
                'status' => $newStatus,
            ]);
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
