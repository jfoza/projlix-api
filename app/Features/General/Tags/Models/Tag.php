<?php

namespace App\Features\General\Tags\Models;

use App\Features\Base\Models\Register;
use App\Features\General\Colors\Models\Color;
use App\Features\Project\Cards\Models\Card;
use App\Features\Project\Projects\Models\Project;
use App\Features\Project\ProjectTag\Models\ProjectTag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Tag extends Register
{
    const ID   = 'id';
    const COLOR_ID = 'color_id';
    const NAME = 'name';
    const ACTIVE = 'active';

    const CREATED_AT  = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'general.tags';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::COLOR_ID,
        self::NAME,
        self::ACTIVE,
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            ProjectTag::tableName(),
            ProjectTag::TAG_ID,
            ProjectTag::PROJECT_ID,
        )->withPivot('id');
    }

    public function cards(): HasManyThrough
    {
        return $this->hasManyThrough(
            Card::class,
            ProjectTag::class,
            ProjectTag::TAG_ID,
            Card::TAG_PROJECT_ID,
            self::ID,
            Card::ID
        );
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}
