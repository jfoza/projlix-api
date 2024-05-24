<?php

namespace App\Features\User\Profiles\Contracts;

use App\Features\User\Profiles\DTO\ProfilesFiltersDTO;
use Illuminate\Support\Collection;

interface ProfilesRepositoryInterface
{
    public function findAll(ProfilesFiltersDTO $profilesFiltersDTO): Collection;
    public function findById(string $id): ?object;
    public function findByUniqueName(string $uniqueName): ?object;
}
