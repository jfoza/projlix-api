<?php

namespace App\Features\User\Profiles\Validations;

use App\Features\User\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\User\Profiles\Models\Profile;
use Illuminate\Support\Collection;

class ProfileHierarchyValidations
{
    public static function administrativeAuth(Collection $profiles): bool
    {
        return (bool) $profiles->firstWhere(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER);
    }

    public static function operationAuth(Collection $profiles): bool
    {
        $profilesArr = [
            ProfileUniqueNameEnum::PROJECT_MANAGER,
            ProfileUniqueNameEnum::TEAM_LEADER,
            ProfileUniqueNameEnum::PROJECT_MEMBER,
        ];

        return (bool) $profiles
            ->whereIn(Profile::UNIQUE_NAME, $profilesArr)
            ->first();
    }
}
