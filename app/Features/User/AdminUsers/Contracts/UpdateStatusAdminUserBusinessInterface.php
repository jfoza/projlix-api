<?php

namespace App\Features\User\AdminUsers\Contracts;

interface UpdateStatusAdminUserBusinessInterface
{
    public function handle(string $userId): object;
}
