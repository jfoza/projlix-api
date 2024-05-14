<?php

namespace App\Features\General\Tags\Contracts;

interface UpdateStatusTagBusinessInterface
{
    public function handle(string $id): object;
}
