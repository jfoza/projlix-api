<?php

namespace App\Features\General\Tags\Contracts;

use Illuminate\Support\Collection;

interface FindAllTagsBusinessInterface
{
    public function handle(): Collection;
}
