<?php

namespace App\Features\User\ProfilesUsers\Models;


use App\Features\Base\Models\Register;

class ProfileUser extends Register
{
    const USER_ID = 'user_id';
    const PROFILE_ID = 'profile_id';

    protected $table = 'user_conf.profiles_users';

    protected $primaryKey = [self::USER_ID, self::PROFILE_ID];

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
        self::PROFILE_ID,
    ];
}
