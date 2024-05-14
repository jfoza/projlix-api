<?php

namespace App\Features\Auth\Services;

use App\Exceptions\AppException;
use App\Features\Auth\Contracts\ShowAuthUserServiceInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Responses\AuthUserResponse;
use App\Features\Auth\Validations\AuthValidations;
use App\Features\User\Profiles\Validations\ProfileHierarchyValidations;
use App\Features\User\Rules\Contracts\RulesRepositoryInterface;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\Traits\UserAbilityTrait;
use App\Shared\Enums\AuthTypesEnum;
use App\Shared\Utils\Hash;

class ShowAuthUserService implements ShowAuthUserServiceInterface
{
    use UserAbilityTrait;

    private ?object $user;
    private AuthDTO $authDTO;

    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly RulesRepositoryInterface $rulesRepository,
        private readonly AuthUserResponse $authUserResponse
    )
    {}

    /**
     * @throws AppException
     */
    public function execute(AuthDTO $authDTO): AuthUserResponse
    {
        $this->setAuthDTO($authDTO);
        $this->setUser($this->getAuthDTO()->email);

        $this->passwordValidation();
        $this->userStatusValidation();

        $profiles = collect($this->getUser()->profile);

        match (true)
        {
            ProfileHierarchyValidations::administrativeAuth($profiles) => $this->validateByAdminMaster(),
            ProfileHierarchyValidations::operationAuth($profiles)      => $this->validateByOperationUsers(),

            default => AuthValidations::dispatchNoProfileException(),
        };

        return $this->getAuthUserResponse();
    }

    private function getAuthDTO(): AuthDTO
    {
        return $this->authDTO;
    }

    private function setAuthDTO(AuthDTO $authDTO): void
    {
        $this->authDTO = $authDTO;
    }

    private function getUser(): ?object
    {
        return $this->user;
    }

    /**
     * @throws AppException
     */
    private function setUser(string $email): void
    {
        if(!$this->user = $this->usersRepository->findByEmail($email))
        {
            AuthValidations::dispatchLoginException();
        }
    }

    /**
     * @throws AppException
     */
    private function passwordValidation(): void
    {
        $authTypeIsEmailPassword = $this->getAuthDTO()->authType == AuthTypesEnum::EMAIL_PASSWORD->value;

        $correctPassword = Hash::compareHash($this->getAuthDTO()->password, $this->getUser()->password);

        if($authTypeIsEmailPassword && !$correctPassword)
        {
            AuthValidations::dispatchLoginException();
        }
    }

    /**
     * @throws AppException
     */
    private function userStatusValidation(): void
    {
        if(!$this->getUser()->active)
        {
            AuthValidations::dispatchInactiveUserException();
        }
    }

    /**
     * @throws AppException
     */
    private function validateByAdminMaster(): void
    {
        if(!$this->getUser()->adminUser)
        {
            AuthValidations::dispatchLoginException();
        }
    }

    /**
     * @throws AppException
     */
    private function validateByOperationUsers(): void
    {
        if(!$this->getUser()->teamUser)
        {
            AuthValidations::dispatchLoginException();
        }
    }

    private function getAuthUserResponse(): AuthUserResponse
    {
        $ability = $this->findAllUserAbility($this->getUser(), $this->rulesRepository);

        $this->authUserResponse->id       = $this->getUser()->id;
        $this->authUserResponse->email    = $this->getUser()->email;
        $this->authUserResponse->fullName = $this->getUser()->name;
        $this->authUserResponse->role     = $this->getUser()->profile;
        $this->authUserResponse->status   = $this->getUser()->active;
        $this->authUserResponse->ability  = $ability;

        return $this->authUserResponse;
    }
}
