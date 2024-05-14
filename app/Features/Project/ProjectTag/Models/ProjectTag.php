<?php
declare(strict_types=1);

namespace App\Features\Project\ProjectTag\Models;

use App\Features\Base\Models\Register;
use App\Features\General\Tags\Models\Tag;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectTag extends Register
{
    const ID         = 'id';
    const PROJECT_ID = 'project_id';
    const TAG_ID     = 'tag_id';

    protected $table = 'project.projects_tags';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    public function tag(): HasMany
    {
        return $this->hasMany(Tag::class, Tag::ID, self::TAG_ID);
    }
}
