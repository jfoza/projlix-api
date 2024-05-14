<?php

namespace App\Features\Project\Cards\Models;

use App\Features\Base\Models\Register;
use App\Features\General\Tags\Models\Tag;
use App\Features\Project\ProjectTag\Models\ProjectTag;
use App\Features\Project\Sections\Models\Section;
use App\Features\User\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Card extends Register
{
    const ID             = 'id';
    const CODE           = 'code';
    const SECTION_ID     = 'section_id';
    const USER_ID        = 'user_id';
    const TAG_PROJECT_ID = 'tag_project_id';
    const DESCRIPTION    = 'description';
    const LIMIT_DATE     = 'limit_date';
    const STATUS         = 'status';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'project.cards';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::CODE,
        self::SECTION_ID,
        self::USER_ID,
        self::TAG_PROJECT_ID,
        self::DESCRIPTION,
        self::LIMIT_DATE,
        self::STATUS,
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tag(): HasOneThrough
    {
        return $this->hasOneThrough(
            Tag::class,
            ProjectTag::class,
            self::ID,
            Tag::ID,
            self::TAG_PROJECT_ID,
            ProjectTag::TAG_ID
        );
    }
}
