<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Icons\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Icons\Business\ShowIconBusiness;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\General\Icons\Models\Icon;
use App\Features\General\Icons\Repositories\IconsRepository;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class ShowIconBusinessTest extends UnitBaseTestCase
{
    private MockObject|IconsRepositoryInterface $iconsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->iconsRepositoryMock = $this->createMock(IconsRepository::class);
    }

    public function getShowIconBusiness(): ShowIconBusiness
    {
        return new ShowIconBusiness($this->iconsRepositoryMock);
    }

    public function test_should_to_list_unique_icon(): void
    {
        $showIconBusiness = $this->getShowIconBusiness();

        $showIconBusiness->setPolicy(
            new Policy([RulesEnum::ICONS_VIEW->value])
        );

        $this
            ->iconsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Icon::ID => Uuid::uuid4Generate()]));

        $result = $showIconBusiness->handle(Uuid::uuid4Generate());

        $this->assertIsObject($result);
    }

    public function test_should_return_exception_if_icon_not_exists(): void
    {
        $showIconBusiness = $this->getShowIconBusiness();

        $showIconBusiness->setPolicy(
            new Policy([RulesEnum::ICONS_VIEW->value])
        );

        $this
            ->iconsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::ICON_NOT_FOUND));

        $showIconBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showIconBusiness = $this->getShowIconBusiness();

        $showIconBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showIconBusiness->handle(Uuid::uuid4Generate());
    }
}
