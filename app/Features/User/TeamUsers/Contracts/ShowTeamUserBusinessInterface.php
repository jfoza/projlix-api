<?php

namespace App\Features\User\TeamUsers\Contracts;

interface ShowTeamUserBusinessInterface
{
    public function handle(string $userId): object;
}
