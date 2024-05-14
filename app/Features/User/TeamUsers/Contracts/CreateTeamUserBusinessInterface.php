<?php

namespace App\Features\User\TeamUsers\Contracts;

use App\Features\User\TeamUsers\Responses\SavedTeamUserResponse;
use App\Features\User\Users\DTO\UserDTO;

interface CreateTeamUserBusinessInterface
{
    public function handle(UserDTO $userDTO): SavedTeamUserResponse;
}
