<?php

namespace App\Features\User\Profiles\Enums;

enum ProfileUniqueNameEnum
{
    const ADMIN_MASTER = 'ADMIN_MASTER';
    const PROJECT_MANAGER = 'PROJECT_MANAGER';
    const TEAM_LEADER = 'TEAM_LEADER';
    const PROJECT_MEMBER = 'PROJECT_MEMBER';

    const PROFILES_BY_ADMIN_MASTER = [
        self::ADMIN_MASTER,
        self::PROJECT_MANAGER,
        self::TEAM_LEADER,
        self::PROJECT_MEMBER,
    ];

    const PROFILES_BY_PROJECT_MANAGER = [
        self::PROJECT_MANAGER,
        self::TEAM_LEADER,
        self::PROJECT_MEMBER,
    ];

    const PROFILES_BY_TEAM_LEADER = [
        self::TEAM_LEADER,
        self::PROJECT_MEMBER,
    ];

    const PROFILES_BY_PROJECT_MEMBER = [
        self::PROJECT_MEMBER,
    ];
}
