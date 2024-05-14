<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Notes\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Notes\Business\CreateNoteBusiness;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\DTO\NoteDTO;
use App\Features\General\Notes\Models\Note;
use App\Features\General\Notes\Repositories\NotesRepository;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class CreateNoteBusinessTest extends UnitBaseTestCase
{
    private MockObject|NotesRepositoryInterface $notesRepositoryMock;
    private MockObject|NoteDTO $noteDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->notesRepositoryMock = $this->createMock(NotesRepository::class);
        $this->noteDtoMock         = $this->createMock(NoteDTO::class);
    }

    public function getCreateNoteBusiness(): CreateNoteBusiness
    {
        return new CreateNoteBusiness(
            $this->notesRepositoryMock,
        );
    }

    public function test_should_create_note(): void
    {
        $createNoteBusiness = $this->getCreateNoteBusiness();

        $createNoteBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_INSERT->value])
        );

        $createNoteBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this
            ->notesRepositoryMock
            ->method('create')
            ->willReturn((object) ([
                Note::ID => Uuid::uuid4Generate(),
                Note::USER_ID => Uuid::uuid4Generate()
            ]));

        $result = $createNoteBusiness->handle($this->noteDtoMock);

        $this->assertIsObject($result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createNoteBusiness = $this->getCreateNoteBusiness();

        $createNoteBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createNoteBusiness->handle($this->noteDtoMock);
    }
}
