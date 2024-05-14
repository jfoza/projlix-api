<?php

namespace App\Features\User\ProfileTypes\Models;

use App\Features\Base\Models\Register;
use App\Features\User\Profiles\Models\Profile;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfileType extends Register
{
    const ID = 'id';
    const DESCRIPTION = 'description';
    const UNIQUE_NAME = 'unique_name';

    protected $table = 'user_conf.profile_types';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    public function profile(): HasMany
    {
        return $this->hasMany(Profile::class, Profile::PROFILE_TYPE_ID, self::ID);
    }
}
