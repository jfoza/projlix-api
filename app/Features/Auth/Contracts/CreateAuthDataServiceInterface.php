<?php

namespace App\Features\Auth\Contracts;

use App\Features\Auth\DTO\AuthDTO;

interface CreateAuthDataServiceInterface
{
    public function execute(AuthDTO $authDTO): object;
}
