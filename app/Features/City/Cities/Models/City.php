<?php

namespace App\Features\City\Cities\Models;

use App\Features\Base\Models\Register;
use App\Features\Person\Persons\Models\Person;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Register
{
    const ID          = 'id';
    const DESCRIPTION = 'description';
    const UF          = 'uf';
    const ACTIVE      = 'active';

    protected $table = 'city.cities';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    public $hidden = ['pivot'];

    protected $fillable = [
        self::DESCRIPTION,
        self::UF,
        self::ACTIVE,
    ];

    public function person(): HasMany
    {
        return $this->hasMany(Person::class, Person::CITY_ID, self::ID);
    }
}
