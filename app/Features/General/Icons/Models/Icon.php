<?php

namespace App\Features\General\Icons\Models;

use App\Features\Base\Models\Register;
use App\Features\Project\Projects\Models\Project;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function project(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
