<?php

namespace App\Features\General\Notes\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\Contracts\RemoveNoteBusinessInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Symfony\Component\HttpFoundation\Response;

class RemoveNoteBusiness extends Business implements RemoveNoteBusinessInterface
{
    public function __construct(
        private readonly NotesRepositoryInterface $notesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): void
    {
        $this->getPolicy()->havePermission(RulesEnum::NOTES_DELETE->value);

        if(!$note = $this->notesRepository->findById($id))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        $this->canAccessNote($note->user_id);

        try
        {
            $this->notesRepository->remove($id);

            Transaction::commit();
        }
        catch (\Exception $exception)
        {
            Transaction::rollBack();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
