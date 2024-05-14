<?php

namespace App\Features\General\Tags\Contracts;

interface RemoveTagBusinessInterface
{
    public function handle(string $id): void;
}
