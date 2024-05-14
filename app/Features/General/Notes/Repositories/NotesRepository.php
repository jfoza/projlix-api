<?php

namespace App\Features\General\Notes\Repositories;

use App\Features\Base\Traits\BuilderTrait;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\DTO\NoteDTO;
use App\Features\General\Notes\DTO\NotesFiltersDTO;
use App\Features\General\Notes\Models\Note;
use App\Features\User\Users\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NotesRepository implements NotesRepositoryInterface
{
    use BuilderTrait;

    public function __construct(private readonly Note $note) {}

    public function findAll(NotesFiltersDTO $notesFiltersDTO): Collection|LengthAwarePaginator
    {
        $notesFiltersDTO->paginationOrder->defineCustomColumnName(Note::CREATED_AT);

        $builder = Note::with(['user'])
            ->whereRelation(
                'user',
                User::ID,
                $notesFiltersDTO->userId
            )
            ->orderBy(
                $notesFiltersDTO->paginationOrder->getColumnName(),
                $notesFiltersDTO->paginationOrder->getColumnOrder(),
            );

        return $this->paginateOrGet($builder, $notesFiltersDTO->paginationOrder);
    }

    public function findById(string $id): ?object
    {
        return Note::with(['user'])->where(Note::ID, $id)->first();
    }

    public function create(NoteDTO $noteDTO): object
    {
        return $this->note->create([
            Note::USER_ID => $noteDTO->userId,
            Note::CONTENT => $noteDTO->content,
        ]);
    }

    public function save(NoteDTO $noteDTO): object
    {
        $updated = [
            Note::ID      => $noteDTO->id,
            Note::CONTENT => $noteDTO->content,
        ];

        $this->note->where(Note::ID, $noteDTO->id)->update($updated);

        return (object) $updated;
    }

    public function remove(string $id): void
    {
        Note::where(Note::ID, $id)->delete();
    }
}
