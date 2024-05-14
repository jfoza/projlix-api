<?php

namespace App\Features\User\Users\Models;

use App\Features\Auth\Models\Auth;
use App\Features\Base\Models\Register;
use App\Features\General\Notes\Models\Note;
use App\Features\Person\Persons\Models\Person;
use App\Features\Project\Cards\Models\Card;
use App\Features\User\AdminUsers\Models\AdminUser;
use App\Features\User\Profiles\Models\Profile;
use App\Features\User\ProfilesUsers\Models\ProfileUser;
use App\Features\User\TeamUsers\Models\TeamUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizeContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticateContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User
    extends Register
    implements AuthenticateContract, AuthorizeContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    const ID        = 'id';
    const PERSON_ID = 'person_id';
    const NAME      = 'name';
    const SHORT_NAME = 'short_name';
    const EMAIL     = 'email';
    const PASSWORD  = 'password';
    const ACTIVE    = 'active';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'user_conf.users';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::ID,
        self::PERSON_ID,
        self::NAME,
        self::SHORT_NAME,
        self::EMAIL,
        self::PASSWORD,
        self::ACTIVE,
    ];

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class)->with('city');
    }

    public function adminUser(): HasOne
    {
        return $this->hasOne(AdminUser::class);
    }

    public function teamUser(): HasOne
    {
        return $this->hasOne(TeamUser::class);
    }

    public function auth(): HasMany
    {
        return $this->hasMany(Auth::class);
    }

    public function card(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function profile(): BelongsToMany
    {
        return $this->belongsToMany(
            Profile::class,
            ProfileUser::tableName(),
            ProfileUser::USER_ID,
            ProfileUser::PROFILE_ID
        );
    }
}
