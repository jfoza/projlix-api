<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\Users\Services;

use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\Models\User;
use App\Features\User\Users\Repositories\UsersRepository;
use App\Features\User\Users\Services\UserUpdateStatusService;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class UserUpdateStatusServiceTest extends TestCase
{
    private MockObject|UsersRepositoryInterface $usersRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock = $this->createMock(UsersRepository::class);
    }

    public function getUserUpdateStatusService(): UserUpdateStatusService
    {
        return new UserUpdateStatusService(
            $this->usersRepositoryMock
        );
    }

    public function test_should_update_user_status()
    {
        $userUpdateStatusService = $this->getUserUpdateStatusService();

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                User::ID => Uuid::uuid4Generate(),
                User::ACTIVE => false
            ]));

        $result = $userUpdateStatusService->execute(
            Uuid::uuid4Generate()
        );

        $this->assertIsObject($result);
    }
}
