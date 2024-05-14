<?php

namespace App\Features\Project\Projects\Models;

use App\Features\Base\Models\Register;
use App\Features\General\Tags\Models\Tag;
use App\Features\Project\ProjectTag\Models\ProjectTag;
use App\Features\Project\Sections\Models\Section;
use App\Features\User\ProjectsTeamUsers\Models\ProjectsTeamUser;
use App\Features\User\TeamUsers\Models\TeamUser;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Register
{
    const ID          = 'id';
    const NAME        = 'name';
    const DESCRIPTION = 'description';
    const ACTIVE      = 'active';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'project.projects';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::NAME,
        self::DESCRIPTION,
        self::ACTIVE,
    ];

    public function teamUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            TeamUser::class,
            ProjectsTeamUser::tableName(),
            ProjectsTeamUser::PROJECT_ID,
            ProjectsTeamUser::TEAM_USER_ID,
        );
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            ProjectTag::tableName(),
            ProjectTag::PROJECT_ID,
            ProjectTag::TAG_ID,
        );
    }
}
