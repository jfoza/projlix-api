<?php

namespace App\Features\User\Profiles\Contracts;

interface ProfilesRepositoryInterface
{
    public function findById(string $id): ?object;
    public function findByUniqueName(string $uniqueName): ?object;
}
