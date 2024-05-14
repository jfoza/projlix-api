<?php

namespace App\Features\General\Notes\Contracts;

use App\Features\General\Notes\DTO\NoteDTO;
use App\Features\General\Notes\DTO\NotesFiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface NotesRepositoryInterface
{
    public function findAll(NotesFiltersDTO $notesFiltersDTO): Collection|LengthAwarePaginator;
    public function findById(string $id): ?object;
    public function create(NoteDTO $noteDTO): object;
    public function save(NoteDTO $noteDTO): object;
    public function remove(string $id): void;
}
