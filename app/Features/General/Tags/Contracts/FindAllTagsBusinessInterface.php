<?php

namespace App\Features\General\Tags\Contracts;

use App\Features\General\Tags\DTO\TagsFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllTagsBusinessInterface
{
    public function handle(TagsFiltersDTO $tagsFiltersDTO): LengthAwarePaginator|Collection;
}
