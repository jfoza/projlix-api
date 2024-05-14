<?php

namespace App\Features\User\Users\Contracts;

interface UserUpdateStatusServiceInterface
{
    public function execute(string $userId): object;
}
