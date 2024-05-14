<?php

namespace App\Features\User\AdminUsers\Contracts;

use App\Features\User\AdminUsers\Responses\SavedAdminUserResponse;
use App\Features\User\Users\DTO\UserDTO;

interface CreateAdminUserBusinessInterface
{
    public function handle(UserDTO $userDTO): SavedAdminUserResponse;
}
