<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Notes\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Notes\Business\ShowNoteBusiness;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\Models\Note;
use App\Features\General\Notes\Repositories\NotesRepository;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class ShowNoteBusinessTest extends UnitBaseTestCase
{
    private MockObject|NotesRepositoryInterface $notesRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->notesRepositoryMock = $this->createMock(NotesRepository::class);
    }

    public function getShowNoteBusiness(): ShowNoteBusiness
    {
        return new ShowNoteBusiness(
            $this->notesRepositoryMock,
        );
    }

    public function test_should_return_unique_note(): void
    {
        $showNoteBusiness = $this->getShowNoteBusiness();

        $showNoteBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_VIEW->value])
        );

        $authUser = $this->getAuthUserMock();

        $showNoteBusiness->setAuthenticatedUser($authUser);

        $this
            ->notesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Note::ID => Uuid::uuid4Generate(),
                Note::USER_ID => $authUser->getId()
            ]));

        $result = $showNoteBusiness->handle(Uuid::uuid4Generate());

        $this->assertIsObject($result);
    }

    public function test_should_return_exception_if_note_not_exists(): void
    {
        $showNoteBusiness = $this->getShowNoteBusiness();

        $showNoteBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_VIEW->value])
        );

        $authUser = $this->getAuthUserMock();

        $showNoteBusiness->setAuthenticatedUser($authUser);

        $this
            ->notesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::REGISTER_NOT_FOUND));

        $showNoteBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_the_note_belongs_to_another_user(): void
    {
        $showNoteBusiness = $this->getShowNoteBusiness();

        $showNoteBusiness->setPolicy(
            new Policy([RulesEnum::NOTES_VIEW->value])
        );

        $authUser = $this->getAuthUserMock();

        $showNoteBusiness->setAuthenticatedUser($authUser);

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

        $showNoteBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showNoteBusiness = $this->getShowNoteBusiness();

        $showNoteBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showNoteBusiness->handle(Uuid::uuid4Generate());
    }
}
