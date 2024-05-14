<?php

namespace App\Features\Project\Cards\Contracts;

interface RemoveCardBusinessInterface
{
    public function handle(string $id): void;
}
