<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Notes\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Base\Pagination\PaginationOrder;
use App\Features\General\Notes\Business\FindAllNotesBusiness;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\DTO\NotesFiltersDTO;
use App\Features\General\Notes\Models\Note;
use App\Features\General\Notes\Repositories\NotesRepository;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class FindAllNotesBusinessTest extends UnitBaseTestCase
{
    private MockObject|NotesRepositoryInterface $notesRepositoryMock;
    private MockObject|NotesFiltersDTO $notesFiltersDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->notesRepositoryMock = $this->createMock(NotesRepository::class);
        $this->notesFiltersDtoMock = $this->createMock(NotesFiltersDTO::class);
    }

    public function getFindAllNotesBusiness(): FindAllNotesBusiness
    {
        return new FindAllNotesBusiness(
            $this->notesRepositoryMock,
        );
    }

    public function getNotes(): Collection
    {
        return Collection::make([
            [
                Note::ID,
                Note::CONTENT,
            ]
        ]);
    }

    public function getPaginatedNotesList(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $this->getNotes(),
            10,
            10,
        );
    }

    public function test_should_return_notes_list(): void
    {
        $findAllNotesBusiness = $this->getFindAllNotesBusiness();

        $findAllNotesBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_VIEW->value])
        );

        $findAllNotesBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this
            ->notesRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $result = $findAllNotesBusiness->handle($this->notesFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_should_return_paginated_notes_list()
    {
        $findAllNotesBusiness = $this->getFindAllNotesBusiness();

        $findAllNotesBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_VIEW->value])
        );

        $findAllNotesBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this->notesFiltersDtoMock->paginationOrder = new PaginationOrder();

        $this->notesFiltersDtoMock->paginationOrder->setPage(1);
        $this->notesFiltersDtoMock->paginationOrder->setPerPage(10);

        $this
            ->notesRepositoryMock
            ->method('findAll')
            ->willReturn($this->getPaginatedNotesList());

        $result = $findAllNotesBusiness->handle($this->notesFiltersDtoMock);

        $this->assertInstanceOf(LengthAwarePaginatorContract::class, $result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllNotesBusiness = $this->getFindAllNotesBusiness();

        $findAllNotesBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllNotesBusiness->handle($this->notesFiltersDtoMock);
    }
}
