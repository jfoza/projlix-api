<?php
declare(strict_types=1);

namespace Tests\Unit\Mocks;

use App\Features\User\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\User\Profiles\Models\Profile;
use App\Shared\Libraries\Uuid;

class ProfileMocks
{
    public static function getAdminMaster(): object
    {
        return (object) ([
            Profile::ID              => Uuid::uuid4Generate(),
            Profile::PROFILE_TYPE_ID => Uuid::uuid4Generate(),
            Profile::DESCRIPTION     => 'Admin Master',
            Profile::UNIQUE_NAME     => ProfileUniqueNameEnum::ADMIN_MASTER->value,
        ]);
    }

    public static function getProjectManager(): object
    {
        return (object) ([
            Profile::ID              => Uuid::uuid4Generate(),
            Profile::PROFILE_TYPE_ID => Uuid::uuid4Generate(),
            Profile::DESCRIPTION     => 'Project Manager',
            Profile::UNIQUE_NAME     => ProfileUniqueNameEnum::PROJECT_MANAGER->value,
        ]);
    }

    public static function getTeamLeader(): object
    {
        return (object) ([
            Profile::ID              => Uuid::uuid4Generate(),
            Profile::PROFILE_TYPE_ID => Uuid::uuid4Generate(),
            Profile::DESCRIPTION     => 'Team Leader',
            Profile::UNIQUE_NAME     => ProfileUniqueNameEnum::TEAM_LEADER->value,
        ]);
    }

    public static function getProjectMember(): object
    {
        return (object) ([
            Profile::ID              => Uuid::uuid4Generate(),
            Profile::PROFILE_TYPE_ID => Uuid::uuid4Generate(),
            Profile::DESCRIPTION     => 'Project Member',
            Profile::UNIQUE_NAME     => ProfileUniqueNameEnum::PROJECT_MEMBER->value,
        ]);
    }
}
