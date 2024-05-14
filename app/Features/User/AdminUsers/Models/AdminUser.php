<?php

namespace App\Features\User\AdminUsers\Models;

use App\Features\Base\Models\Register;
use App\Features\User\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminUser extends Register
{
    use HasFactory;

    const ID = 'id';
    const USER_ID = 'user_id';
    const CREATED_AT = 'created_at';

    protected $table = 'user_conf.admin_users';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
