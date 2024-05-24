<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\User\AdminUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\User\AdminUsers\Business\UpdateStatusAdminUserBusiness;
use App\Features\User\Users\Contracts\UserUpdateStatusServiceInterface;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class UpdateStatusAdminUserBusinessTest extends UnitBaseTestCase
{
    private MockObject|UserUpdateStatusServiceInterface $userUpdateStatusService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userUpdateStatusService = $this->createMock(UserUpdateStatusServiceInterface::class);
    }

    public function getUpdateStatusAdminUserBusiness(): UpdateStatusAdminUserBusiness
    {
        return new UpdateStatusAdminUserBusiness($this->userUpdateStatusService);
    }

    public function test_should_to_update_status_user()
    {
        $updateStatusAdminUserBusiness = $this->getUpdateStatusAdminUserBusiness();

        $updateStatusAdminUserBusiness->setPolicy(
            new Policy([RulesEnum::ADMIN_USERS_UPDATE->value])
        );

        $result = $updateStatusAdminUserBusiness->handle(Uuid::uuid4Generate());

        $this->assertIsObject($result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateStatusAdminUserBusiness = $this->getUpdateStatusAdminUserBusiness();

        $updateStatusAdminUserBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateStatusAdminUserBusiness->handle(Uuid::uuid4Generate());
    }
}
