<?php

namespace App\Features\Project\Sections\Contracts;

interface SectionReorderingBusinessInterface
{
    public function handle(string $sectionId, int $newOrder): void;
}
