<?php

namespace App\Features\General\Notes\Controllers;

use App\Features\General\Notes\Contracts\CreateNoteBusinessInterface;
use App\Features\General\Notes\Contracts\FindAllNotesBusinessInterface;
use App\Features\General\Notes\Contracts\RemoveNoteBusinessInterface;
use App\Features\General\Notes\Contracts\ShowNoteBusinessInterface;
use App\Features\General\Notes\Contracts\UpdateNoteBusinessInterface;
use App\Features\General\Notes\DTO\NoteDTO;
use App\Features\General\Notes\DTO\NotesFiltersDTO;
use App\Features\General\Notes\Requests\NotesFiltersRequest;
use App\Features\General\Notes\Requests\NotesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class NotesController
{
    public function __construct(
        private FindAllNotesBusinessInterface $findAllNotesBusiness,
        private ShowNoteBusinessInterface     $showNoteBusiness,
        private CreateNoteBusinessInterface   $createNoteBusiness,
        private UpdateNoteBusinessInterface   $updateNoteBusiness,
        private RemoveNoteBusinessInterface   $removeNoteBusiness,
    ) {}

    public function index(
        NotesFiltersRequest $notesFiltersRequest,
        NotesFiltersDTO $notesFiltersDTO
    ): JsonResponse
    {
        $notesFiltersDTO->paginationOrder->setPage($notesFiltersRequest->page);
        $notesFiltersDTO->paginationOrder->setPerPage($notesFiltersRequest->perPage);

        $notes = $this->findAllNotesBusiness->handle($notesFiltersDTO);

        return response()->json($notes, Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->id;

        $note = $this->showNoteBusiness->handle($id);

        return response()->json($note, Response::HTTP_OK);
    }

    public function insert(
        NotesRequest $notesRequest,
        NoteDTO $noteDTO,
    ): JsonResponse
    {
        $noteDTO->content = $notesRequest['content'];

        $note = $this->createNoteBusiness->handle($noteDTO);

        return response()->json($note, Response::HTTP_OK);
    }

    public function update(
        NotesRequest $notesRequest,
        NoteDTO $noteDTO,
    ): JsonResponse
    {
        $noteDTO->id      = $notesRequest->id;
        $noteDTO->content = $notesRequest['content'];

        $note = $this->updateNoteBusiness->handle($noteDTO);

        return response()->json($note, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removeNoteBusiness->handle($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
