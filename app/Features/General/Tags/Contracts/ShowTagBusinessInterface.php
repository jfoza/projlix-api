<?php

namespace App\Features\General\Tags\Contracts;

interface ShowTagBusinessInterface
{
    public function handle(string $id): object;
}
