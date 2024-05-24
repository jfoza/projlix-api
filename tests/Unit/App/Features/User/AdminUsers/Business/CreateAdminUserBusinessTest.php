<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\AdminUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\User\AdminUsers\Business\CreateAdminUserBusiness;
use App\Features\User\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\User\AdminUsers\Responses\SavedAdminUserResponse;
use App\Features\User\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\DTO\UserDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Mocks\ProfileMocks;
use Tests\Unit\Mocks\UserMocks;
use Tests\Unit\UnitBaseTestCase;

class CreateAdminUserBusinessTest extends UnitBaseTestCase
{
    private MockObject|UsersRepositoryInterface $usersRepositoryMock;
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;
    private MockObject|ProfilesRepositoryInterface $profilesRepositoryMock;

    private MockObject|UserDTO $userDTOMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock      = $this->createMock(UsersRepositoryInterface::class);
        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepositoryInterface::class);
        $this->profilesRepositoryMock   = $this->createMock(ProfilesRepositoryInterface::class);

        $this->userDTOMock = $this->createMock(UserDTO::class);

        $this->setUserDto();
    }

    public function getCreateAdminUserBusiness(): CreateAdminUserBusiness
    {
        return new CreateAdminUserBusiness(
            $this->usersRepositoryMock,
            $this->adminUsersRepositoryMock,
            $this->profilesRepositoryMock,
        );
    }

    public function setUserDto(): void
    {
        $this->userDTOMock->name     = 'John Doe';
        $this->userDTOMock->email    = 'john-doe@email.com';
    }

    public function test_should_to_create_new_admin_user()
    {
        $createAdminUserBusiness = $this->getCreateAdminUserBusiness();

        $createAdminUserBusiness->setPolicy(
            new Policy([RulesEnum::ADMIN_USERS_INSERT->value])
        );

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('create')
            ->willReturn(UserMocks::getUserSaved());

        $this
            ->profilesRepositoryMock
            ->method('findByUniqueName')
            ->willReturn(ProfileMocks::getAdminMaster());

        $result = $createAdminUserBusiness->handle($this->userDTOMock);

        $this->assertInstanceOf(SavedAdminUserResponse::class, $result);
    }

    public function test_should_return_exception_if_email_already_exists()
    {
        $createAdminUserBusiness = $this->getCreateAdminUserBusiness();

        $createAdminUserBusiness->setPolicy(
            new Policy([RulesEnum::ADMIN_USERS_INSERT->value])
        );

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UserMocks::getUserSaved());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::EMAIL_ALREADY_EXISTS));

        $createAdminUserBusiness->handle($this->userDTOMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createAdminUserBusiness = $this->getCreateAdminUserBusiness();

        $createAdminUserBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createAdminUserBusiness->handle($this->userDTOMock);
    }
}
