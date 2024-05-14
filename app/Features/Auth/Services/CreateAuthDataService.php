<?php

namespace App\Features\Auth\Services;

use App\Features\Auth\Contracts\AuthRepositoryInterface;
use App\Features\Auth\Contracts\CreateAuthDataServiceInterface;
use App\Features\Auth\DTO\AuthDTO;

class CreateAuthDataService implements CreateAuthDataServiceInterface
{
    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {}

    public function execute(AuthDTO $authDTO): object
    {
        return $this->authRepository->create($authDTO);
    }
}
