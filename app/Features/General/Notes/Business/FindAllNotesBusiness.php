<?php

namespace App\Features\General\Notes\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Notes\Contracts\FindAllNotesBusinessInterface;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\DTO\NotesFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllNotesBusiness extends Business implements FindAllNotesBusinessInterface
{
    public function __construct(
        private readonly NotesRepositoryInterface $notesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(NotesFiltersDTO $notesFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::NOTES_VIEW->value);

        $notesFiltersDTO->userId = $this->getAuthenticatedUserId();

        return $this->notesRepository->findAll($notesFiltersDTO);
    }
}
