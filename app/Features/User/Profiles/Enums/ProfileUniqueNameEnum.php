<?php

namespace App\Features\User\Profiles\Enums;

enum ProfileUniqueNameEnum: string
{
    case ADMIN_MASTER = 'ADMIN_MASTER';
    case PROJECT_MANAGER = 'PROJECT_MANAGER';
    case TEAM_LEADER = 'TEAM_LEADER';
    case PROJECT_MEMBER = 'PROJECT_MEMBER';
}
