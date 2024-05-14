<?php

namespace App\Features\Auth\Models;

use App\Features\Base\Models\Register;
use App\Features\User\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auth extends Register
{
    const ID           = 'id';
    const USER_ID      = 'user_id';
    const INITIAL_DATE = 'initial_date';
    const FINAL_DATE   = 'final_date';
    const TOKEN        = 'token';
    const IP_ADDRESS   = 'ip_address';
    const AUTH_TYPE    = 'auth_type';
    const ACTIVE       = 'active';

    protected $table = 'user_conf.auth';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::ID,
        self::USER_ID,
        self::INITIAL_DATE,
        self::FINAL_DATE,
        self::TOKEN,
        self::IP_ADDRESS,
        self::AUTH_TYPE,
        self::ACTIVE,
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class, self::USER_ID, User::ID);
    }
}
