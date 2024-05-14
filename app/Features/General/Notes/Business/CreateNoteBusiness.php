<?php

namespace App\Features\General\Notes\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Notes\Contracts\CreateNoteBusinessInterface;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\DTO\NoteDTO;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateNoteBusiness extends Business implements CreateNoteBusinessInterface
{
    public function __construct(
        private readonly NotesRepositoryInterface $notesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(NoteDTO $noteDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::NOTES_INSERT->value);

        $noteDTO->userId = $this->getAuthenticatedUserId();

        Transaction::beginTransaction();

        try
        {
            $result = $this->notesRepository->create($noteDTO);

            Transaction::commit();

            return $result;
        }
        catch (\Exception $exception)
        {
            Transaction::rollBack();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
