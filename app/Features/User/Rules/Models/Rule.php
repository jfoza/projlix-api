<?php

namespace App\Features\User\Rules\Models;

use App\Features\Base\Models\Register;
use App\Features\User\Profiles\Models\Profile;
use App\Features\User\ProfilesRules\Models\ProfileRule;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rule extends Register
{
    const ID = 'id';
    const DESCRIPTION = 'description';
    const SUBJECT = 'subject';
    const ACTION = 'action';
    const ACTIVE = 'active';

    protected $table = 'user_conf.rules';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [];

    public function profile (): BelongsToMany
    {
        return $this->belongsToMany(
            Profile::class,
            ProfileRule::tableName(),
            ProfileRule::RULE_ID,
            ProfileRule::PROFILE_ID,
        );
    }
}
