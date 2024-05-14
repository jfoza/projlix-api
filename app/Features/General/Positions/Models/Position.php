<?php

namespace App\Features\General\Positions\Models;

use App\Features\Base\Models\Register;
use App\Features\User\TeamUsers\Models\TeamUser;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Position extends Register
{
    const ID          = 'id';
    const NAME        = 'name';
    const DESCRIPTION = 'description';
    const ACTIVE      = 'active';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'general.positions';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::NAME,
        self::DESCRIPTION,
        self::ACTIVE,
    ];

    public function teamUser(): HasOne
    {
        return $this->hasOne(TeamUser::class);
    }
}
