<?php

namespace App\Features\General\Icons\Contracts;

use Illuminate\Support\Collection;

interface FindAllIconsBusinessInterface
{
    public function handle(): Collection;
}
