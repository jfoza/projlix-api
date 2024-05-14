<?php

namespace App\Features\General\Notes\Contracts;

use App\Features\General\Notes\DTO\NotesFiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllNotesBusinessInterface
{
    public function handle(NotesFiltersDTO $notesFiltersDTO): LengthAwarePaginator|Collection;
}
