<?php

namespace App\Features\General\Notes\Models;

use App\Features\Base\Models\Register;
use App\Features\User\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Register
{
    const ID         = 'id';
    const USER_ID    = 'user_id';
    const CONTENT    = 'content';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'general.notes';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
        self::CONTENT,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, self::USER_ID, User::ID);
    }
}
