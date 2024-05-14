<?php

namespace App\Features\Project\Sections\Models;

use App\Features\Base\Models\Register;
use App\Features\General\Colors\Models\Color;
use App\Features\General\Icons\Models\Icon;
use App\Features\Project\Cards\Models\Card;
use App\Features\Project\Projects\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Register
{
    const ID         = 'id';
    const PROJECT_ID = 'project_id';
    const COLOR_ID   = 'color_id';
    const ICON_ID    = 'icon_id';
    const NAME       = 'name';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'project.sections';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::NAME,
        self::PROJECT_ID,
        self::COLOR_ID,
        self::ICON_ID,
        self::NAME,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function icon(): BelongsTo
    {
        return $this->belongsTo(Icon::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
