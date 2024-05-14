<?php

namespace App\Features\General\Colors\Contracts;

interface ShowColorBusinessInterface
{
    public function handle(string $id): object;
}
