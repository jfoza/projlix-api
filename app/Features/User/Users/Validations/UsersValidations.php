<?php

namespace App\Features\User\Users\Validations;

use App\Exceptions\AppException;
use App\Features\User\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Utils\Hash;
use Symfony\Component\HttpFoundation\Response;

class UsersValidations
{
    /**
     * @throws AppException
     */
    public static function validateUserExistsById(
        string $userId,
        UsersRepositoryInterface $usersRepository
    ): object
    {
        if(!$user = $usersRepository->findById($userId))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $user;
    }

    /**
     * @throws AppException
     */
    public static function checkIfPasswordsMatch(
        string $payload,
        string $hashed
    ): void
    {
        if(!Hash::compareHash($payload, $hashed))
        {
            throw new AppException(
                MessagesEnum::INVALID_CURRENT_PASSWORD,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function emailAlreadyExists(
        string $email,
        UsersRepositoryInterface $usersRepository,
    ): void
    {
        if(!empty($usersRepository->findByEmail($email))) {
            throw new AppException(
                MessagesEnum::EMAIL_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function emailAlreadyExistsInUpdate(
        string $userId,
        string $email,
        UsersRepositoryInterface $usersRepository,
    ): void
    {
        $user = $usersRepository->findByEmail($email);

        if($user && $user->id != $userId)
        {
            throw new AppException(
                MessagesEnum::EMAIL_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function emailAlreadyExistsUpdateException(): void
    {
        throw new AppException(
            MessagesEnum::EMAIL_ALREADY_EXISTS,
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @throws AppException
     */
    public static function profileExists(
        string $profileId,
        ProfilesRepositoryInterface $profilesRepository,
    ): object
    {
        if(!$profile = $profilesRepository->findById($profileId))
        {
            throw new AppException(
                MessagesEnum::PROFILE_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $profile;
    }

    /**
     * @throws AppException
     */
    public static function isActiveUser(bool $active): void
    {
        if (!$active) {
            throw new AppException(
                MessagesEnum::INACTIVE_USER,
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
