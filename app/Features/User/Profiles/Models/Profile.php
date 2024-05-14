<?php

namespace App\Features\User\Profiles\Models;

use App\Features\Base\Models\Register;
use App\Features\User\ProfilesRules\Models\ProfileRule;
use App\Features\User\ProfilesUsers\Models\ProfileUser;
use App\Features\User\ProfileTypes\Models\ProfileType;
use App\Features\User\Rules\Models\Rule;
use App\Features\User\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Profile extends Register
{
    use HasFactory;

    const ID              = 'id';
    const PROFILE_TYPE_ID = 'profile_type_id';
    const DESCRIPTION     = 'description';
    const UNIQUE_NAME     = 'unique_name';
    const ACTIVE          = 'active';
    const CREATED_AT      = 'created_at';
    const UPDATED_AT      = 'updated_at';

    protected $table = 'user_conf.profiles';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::ID,
        self::PROFILE_TYPE_ID,
        self::DESCRIPTION,
        self::UNIQUE_NAME,
        self::ACTIVE,
    ];

    public function profileType (): BelongsTo
    {
        return $this->belongsTo(ProfileType::class, self::PROFILE_TYPE_ID, ProfileType::ID);
    }

    public function user (): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            ProfileUser::tableName(),
            ProfileUser::PROFILE_ID,
            ProfileUser::USER_ID,
        );
    }

    public function rule (): BelongsToMany
    {
        return $this->belongsToMany(
            Rule::class,
            ProfileRule::tableName(),
            ProfileRule::PROFILE_ID,
            ProfileRule::RULE_ID
        );
    }
}
