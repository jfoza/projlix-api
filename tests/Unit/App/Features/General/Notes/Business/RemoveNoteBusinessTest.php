<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Notes\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Notes\Business\RemoveNoteBusiness;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\Models\Note;
use App\Features\General\Notes\Repositories\NotesRepository;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class RemoveNoteBusinessTest extends UnitBaseTestCase
{
    private MockObject|NotesRepositoryInterface $notesRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->notesRepositoryMock = $this->createMock(NotesRepository::class);
    }

    public function getRemoveNoteBusiness(): RemoveNoteBusiness
    {
        return new RemoveNoteBusiness(
            $this->notesRepositoryMock,
        );
    }

    public function test_should_remove_unique_note(): void
    {
        $removeNoteBusiness = $this->getRemoveNoteBusiness();

        $removeNoteBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_DELETE->value])
        );

        $authUser = $this->getAuthUserMock();

        $removeNoteBusiness->setAuthenticatedUser($authUser);

        $this
            ->notesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Note::ID => Uuid::uuid4Generate(),
                Note::USER_ID => $authUser->getId()
            ]));

        $removeNoteBusiness->handle(Uuid::uuid4Generate());

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_note_not_exists(): void
    {
        $removeNoteBusiness = $this->getRemoveNoteBusiness();

        $removeNoteBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_DELETE->value])
        );

        $authUser = $this->getAuthUserMock();

        $removeNoteBusiness->setAuthenticatedUser($authUser);

        $this
            ->notesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::REGISTER_NOT_FOUND));

        $removeNoteBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_the_note_belongs_to_another_user(): void
    {
        $removeNoteBusiness = $this->getRemoveNoteBusiness();

        $removeNoteBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_DELETE->value])
        );

        $authUser = $this->getAuthUserMock();

        $removeNoteBusiness->setAuthenticatedUser($authUser);

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

        $removeNoteBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeNoteBusiness = $this->getRemoveNoteBusiness();

        $removeNoteBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeNoteBusiness->handle(Uuid::uuid4Generate());
    }
}
