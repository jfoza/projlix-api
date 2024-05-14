<?php

namespace App\Features\Project\Sections\Contracts;

interface RemoveSectionBusinessInterface
{
    public function handle(string $id): void;
}
