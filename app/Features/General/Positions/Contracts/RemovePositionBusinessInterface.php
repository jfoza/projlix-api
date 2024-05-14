<?php

namespace App\Features\General\Positions\Contracts;

interface RemovePositionBusinessInterface
{
    public function execute(string $id): void;
}
