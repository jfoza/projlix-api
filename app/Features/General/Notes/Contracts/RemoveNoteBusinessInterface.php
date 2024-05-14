<?php

namespace App\Features\General\Notes\Contracts;

interface RemoveNoteBusinessInterface
{
    public function handle(string $id): void;
}
