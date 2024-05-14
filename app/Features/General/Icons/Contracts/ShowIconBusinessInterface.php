<?php

namespace App\Features\General\Icons\Contracts;

interface ShowIconBusinessInterface
{
    public function handle(string $id): object;
}
