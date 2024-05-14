<?php

namespace App\Features\Project\Sections\Contracts;

use App\Features\Project\Sections\DTO\SectionsFiltersDTO;
use Illuminate\Support\Collection;

interface FindAllSectionsBusinessInterface
{
    public function handle(SectionsFiltersDTO $sectionsFiltersDTO): Collection;
}
