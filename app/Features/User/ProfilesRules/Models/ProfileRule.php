<?php

namespace App\Features\User\ProfilesRules\Models;


use App\Features\Base\Models\Register;

class ProfileRule extends Register
{
    const RULE_ID = 'rule_id';
    const PROFILE_ID = 'profile_id';

    protected $table = 'user_conf.profiles_rules';

    protected $primaryKey = [self::RULE_ID, self::PROFILE_ID];

    protected $keyType = 'string';

    protected $fillable = [
        self::RULE_ID,
        self::PROFILE_ID,
    ];
}
