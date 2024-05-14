<?php

namespace App\Features\Project\Sections\Contracts;

interface ShowSectionBusinessInterface
{
    public function handle(string $id): object;
}
