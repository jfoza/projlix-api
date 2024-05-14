<?php

namespace App\Features\General\Tags\Contracts;

use App\Features\General\Tags\DTO\TagsDTO;

interface CreateTagBusinessInterface
{
    public function handle(TagsDTO $tagsDTO): object;
}
