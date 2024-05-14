<?php

namespace App\Features\General\Icons\Models;

use App\Features\Base\Models\Register;

class Icon extends Register
{
    const ID   = 'id';
    const TYPE = 'type';
    const NAME = 'name';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'general.icons';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::TYPE,
        self::NAME,
    ];
}
