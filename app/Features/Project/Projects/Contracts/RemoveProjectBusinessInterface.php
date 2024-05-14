<?php

namespace App\Features\Project\Projects\Contracts;

interface RemoveProjectBusinessInterface
{
    public function handle(string $id);
}
