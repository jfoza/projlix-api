<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\AdminUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\User\AdminUsers\Business\UpdateAdminUserBusiness;
use App\Features\User\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\User\AdminUsers\Responses\SavedAdminUserResponse;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\DTO\UserDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Mocks\UserMocks;
use Tests\Unit\UnitBaseTestCase;

class UpdateAdminUserBusinessTest extends UnitBaseTestCase
{
    private MockObject|UsersRepositoryInterface $usersRepositoryMock;
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;

    private MockObject|UserDTO $userDTOMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock = $this->createMock(UsersRepositoryInterface::class);
        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepositoryInterface::class);

        $this->userDTOMock = $this->createMock(UserDTO::class);

        $this->setUserDto();
    }

    public function getUpdateAdminUserBusiness(): UpdateAdminUserBusiness
    {
        return new UpdateAdminUserBusiness(
            $this->usersRepositoryMock,
            $this->adminUsersRepositoryMock,
        );
    }

    public function setUserDto(): void
    {
        $this->userDTOMock->id    = Uuid::uuid4Generate();
        $this->userDTOMock->name  = 'John Doe';
        $this->userDTOMock->email = 'john-doe@email.com';
    }

    public function test_should_to_update_admin_user()
    {
        $updateAdminUserBusiness = $this->getUpdateAdminUserBusiness();

        $updateAdminUserBusiness->setPolicy(
            new Policy([RulesEnum::ADMIN_USERS_ADMIN_MASTER_UPDATE->value])
        );

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(UserMocks::getUserSaved());

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('update')
            ->willReturn(UserMocks::getUserSaved());

        $result = $updateAdminUserBusiness->handle($this->userDTOMock);

        $this->assertInstanceOf(SavedAdminUserResponse::class, $result);
    }

    public function test_should_throw_exception_when_not_found()
    {
        $updateAdminUserBusiness = $this->getUpdateAdminUserBusiness();

        $updateAdminUserBusiness->setPolicy(
            new Policy([RulesEnum::ADMIN_USERS_ADMIN_MASTER_UPDATE->value])
        );

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $updateAdminUserBusiness->handle($this->userDTOMock);
    }

    public function test_should_return_exception_if_email_already_exists()
    {
        $updateAdminUserBusiness = $this->getUpdateAdminUserBusiness();

        $updateAdminUserBusiness->setPolicy(
            new Policy([RulesEnum::ADMIN_USERS_ADMIN_MASTER_UPDATE->value])
        );

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(UserMocks::getUserSaved());

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UserMocks::getUserSaved());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::EMAIL_ALREADY_EXISTS));

        $updateAdminUserBusiness->handle($this->userDTOMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateAdminUserBusiness = $this->getUpdateAdminUserBusiness();

        $updateAdminUserBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateAdminUserBusiness->handle($this->userDTOMock);
    }
}
