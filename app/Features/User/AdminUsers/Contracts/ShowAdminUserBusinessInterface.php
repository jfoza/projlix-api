<?php

namespace App\Features\User\AdminUsers\Contracts;

interface ShowAdminUserBusinessInterface
{
    public function handle(string $userId): object;
}
