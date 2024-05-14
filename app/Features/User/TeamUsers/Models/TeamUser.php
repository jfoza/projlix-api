<?php

namespace App\Features\User\TeamUsers\Models;

use App\Features\Base\Models\Register;
use App\Features\General\Positions\Models\Position;
use App\Features\Project\Projects\Models\Project;
use App\Features\User\ProjectsTeamUsers\Models\ProjectsTeamUser;
use App\Features\User\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TeamUser extends Register
{
    use HasFactory;

    const ID = 'id';
    const USER_ID = 'user_id';
    const POSITION_ID = 'position_id';
    const CREATED_AT = 'created_at';

    protected $table = 'user_conf.team_users';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
        self::POSITION_ID,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            ProjectsTeamUser::tableName(),
            ProjectsTeamUser::TEAM_USER_ID,
            ProjectsTeamUser::PROJECT_ID,
        );
    }
}
