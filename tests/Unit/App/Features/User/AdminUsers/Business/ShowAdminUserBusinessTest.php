<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\AdminUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\User\AdminUsers\Business\ShowAdminUserBusiness;
use App\Features\User\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\User\Users\Models\User;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class ShowAdminUserBusinessTest extends UnitBaseTestCase
{
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepositoryInterface::class);
    }

    public function getShowAdminUserBusiness(): ShowAdminUserBusiness
    {
        return new ShowAdminUserBusiness(
            $this->adminUsersRepositoryMock,
        );
    }

    public function test_should_find_unique_admin_user()
    {
        $showAdminUserBusiness = $this->getShowAdminUserBusiness();

        $showAdminUserBusiness->setPolicy(
            new Policy([RulesEnum::ADMIN_USERS_VIEW->value])
        );

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn((object) ([User::ID => Uuid::uuid4Generate()]));

        $result = $showAdminUserBusiness->handle(Uuid::uuid4Generate());

        $this->assertIsObject($result);
    }

    public function test_should_throw_exception_when_not_found()
    {
        $showAdminUserBusiness = $this->getShowAdminUserBusiness();

        $showAdminUserBusiness->setPolicy(
            new Policy([RulesEnum::ADMIN_USERS_VIEW->value])
        );

        $this
            ->adminUsersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $showAdminUserBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showAdminUserBusiness = $this->getShowAdminUserBusiness();

        $showAdminUserBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showAdminUserBusiness->handle(Uuid::uuid4Generate());
    }
}
