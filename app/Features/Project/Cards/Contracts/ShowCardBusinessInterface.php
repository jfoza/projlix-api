<?php

namespace App\Features\Project\Cards\Contracts;

interface ShowCardBusinessInterface
{
    public function handle(string $id): object;
}
