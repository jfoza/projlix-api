<?php
declare(strict_types=1);

namespace App\Features\User\AdminUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\User\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\User\AdminUsers\Contracts\CreateAdminUserBusinessInterface;
use App\Features\User\AdminUsers\Responses\SavedAdminUserResponse;
use App\Features\User\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\User\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\DTO\UserDTO;
use App\Features\User\Users\Validations\UsersValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;

class CreateAdminUserBusiness extends Business implements CreateAdminUserBusinessInterface
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
        private readonly ProfilesRepositoryInterface $profilesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(UserDTO $userDTO): SavedAdminUserResponse
    {
        $this->getPolicy()->havePermission(RulesEnum::ADMIN_USERS_INSERT->value);

        UsersValidations::emailAlreadyExists($userDTO->email, $this->usersRepository);

        Transaction::beginTransaction();

        try
        {
            $userDTO->password  = Hash::generateHash(RandomStringHelper::alnumGenerate(6));
            $userDTO->shortName = strtoupper(substr($userDTO->name, 0, 2));

            $userCreated = $this->usersRepository->create($userDTO);

            $this->adminUsersRepository->create($userCreated->id);

            $adminMasterProfile = $this
                ->profilesRepository
                ->findByUniqueName(ProfileUniqueNameEnum::ADMIN_MASTER);

            $this->usersRepository->saveProfiles($userCreated->id, [$adminMasterProfile->id]);

            Transaction::commit();

            return SavedAdminUserResponse::setUp(
                $userCreated->id,
                $userCreated->name,
                $userCreated->email,
                $userCreated->active,
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
