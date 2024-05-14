<?php

namespace App\Features\General\Notes\Contracts;

use App\Features\General\Notes\DTO\NoteDTO;

interface UpdateNoteBusinessInterface
{
    public function handle(NoteDTO $noteDTO): object;
}
