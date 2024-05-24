<?php

namespace App\Features\User\Profiles\Contracts;

use App\Features\User\Profiles\DTO\ProfilesFiltersDTO;
use Illuminate\Support\Collection;

interface FindAllProfilesBusinessInterface
{
    public function handle(ProfilesFiltersDTO $profilesFiltersDTO): Collection;
}
