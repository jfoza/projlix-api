<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Notes\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Notes\Business\UpdateNoteBusiness;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\DTO\NoteDTO;
use App\Features\General\Notes\Models\Note;
use App\Features\General\Notes\Repositories\NotesRepository;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class UpdateNoteBusinessTest extends UnitBaseTestCase
{
    private MockObject|NotesRepositoryInterface $notesRepositoryMock;
    private MockObject|NoteDTO $noteDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->notesRepositoryMock = $this->createMock(NotesRepository::class);
        $this->noteDtoMock         = $this->createMock(NoteDTO::class);

        $this->noteDtoMock->id      = Uuid::uuid4Generate();
        $this->noteDtoMock->content = 'content';
    }

    public function getUpdateNoteBusiness(): UpdateNoteBusiness
    {
        return new UpdateNoteBusiness(
            $this->notesRepositoryMock,
        );
    }

    public function test_should_update_note(): void
    {
        $updateNoteBusiness = $this->getUpdateNoteBusiness();

        $updateNoteBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_UPDATE->value])
        );

        $authUser = $this->getAuthUserMock();

        $updateNoteBusiness->setAuthenticatedUser($authUser);

        $this
            ->notesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Note::ID => Uuid::uuid4Generate(),
                Note::USER_ID => $authUser->getId()
            ]));

        $this
            ->notesRepositoryMock
            ->method('save')
            ->willReturn((object) ([
                Note::ID => Uuid::uuid4Generate(),
                Note::USER_ID => $authUser->getId()
            ]));

        $result = $updateNoteBusiness->handle($this->noteDtoMock);

        $this->assertIsObject($result);
    }

    public function test_should_return_exception_if_note_not_exists(): void
    {
        $updateNoteBusiness = $this->getUpdateNoteBusiness();

        $updateNoteBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_UPDATE->value])
        );

        $authUser = $this->getAuthUserMock();

        $updateNoteBusiness->setAuthenticatedUser($authUser);

        $this
            ->notesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::REGISTER_NOT_FOUND));

        $updateNoteBusiness->handle($this->noteDtoMock);
    }

    public function test_should_return_exception_if_the_note_belongs_to_another_user(): void
    {
        $updateNoteBusiness = $this->getUpdateNoteBusiness();

        $updateNoteBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_UPDATE->value])
        );

        $updateNoteBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this
            ->notesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Note::ID => Uuid::uuid4Generate(),
                Note::USER_ID => Uuid::uuid4Generate()
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::REGISTER_NOT_ALLOWED));

        $updateNoteBusiness->handle($this->noteDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateNoteBusiness = $this->getUpdateNoteBusiness();

        $updateNoteBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateNoteBusiness->handle($this->noteDtoMock);
    }
}
