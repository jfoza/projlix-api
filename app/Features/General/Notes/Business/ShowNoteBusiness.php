<?php

namespace App\Features\General\Notes\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\Contracts\ShowNoteBusinessInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Symfony\Component\HttpFoundation\Response;

class ShowNoteBusiness extends Business implements ShowNoteBusinessInterface
{
    public function __construct(
        private readonly NotesRepositoryInterface $notesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::NOTES_VIEW->value);

        if(!$note = $this->notesRepository->findById($id))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        $this->canAccessNote($note->user_id);

        return $note;
    }
}
