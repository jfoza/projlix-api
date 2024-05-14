<?php

namespace App\Features\Project\Sections\Contracts;

use App\Features\Project\Sections\DTO\SectionsDTO;

interface CreateSectionBusinessInterface
{
    public function handle(SectionsDTO $sectionsDTO): object;
}
