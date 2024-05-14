<?php

namespace App\Features\General\Notes\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\Contracts\UpdateNoteBusinessInterface;
use App\Features\General\Notes\DTO\NoteDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Symfony\Component\HttpFoundation\Response;

class UpdateNoteBusiness extends Business implements UpdateNoteBusinessInterface
{
    public function __construct(
        private readonly NotesRepositoryInterface $notesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(NoteDTO $noteDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::NOTES_UPDATE->value);

        if(!$note = $this->notesRepository->findById($noteDTO->id))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        $this->canAccessNote($note->user_id);

        try
        {
            $result = $this->notesRepository->save($noteDTO);

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
