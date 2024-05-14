<?php

namespace App\Features\User\ProjectsTeamUsers\Models;

use App\Features\Base\Models\Register;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectsTeamUser extends Register
{
    use HasFactory;

    const ID = 'id';
    const TEAM_USER_ID = 'team_user_id';
    const PROJECT_ID = 'project_id';
    const CREATED_AT = 'created_at';

    protected $table = 'user_conf.projects_team_users';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';
}
