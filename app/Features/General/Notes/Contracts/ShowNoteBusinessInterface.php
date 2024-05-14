<?php

namespace App\Features\General\Notes\Contracts;

interface ShowNoteBusinessInterface
{
    public function handle(string $id): object;
}
