<?php

namespace App\Features\User\TeamUsers\Contracts;

interface UpdateStatusTeamUserBusinessInterface
{
    public function handle(string $userId): object;
}
