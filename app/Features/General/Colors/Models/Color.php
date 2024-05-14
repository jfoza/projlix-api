<?php

namespace App\Features\General\Colors\Models;

use App\Features\Base\Models\Register;

class Color extends Register
{
    const ID          = 'id';
    const HEXADECIMAL = 'hexadecimal';
    const CREATED_AT  = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'general.colors';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::HEXADECIMAL,
    ];
}
