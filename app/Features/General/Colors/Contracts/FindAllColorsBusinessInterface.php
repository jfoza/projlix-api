<?php

namespace App\Features\General\Colors\Contracts;

use Illuminate\Support\Collection;

interface FindAllColorsBusinessInterface
{
    public function handle(): Collection;
}
