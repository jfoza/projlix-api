<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Colors\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Colors\Business\ShowColorBusiness;
use App\Features\General\Colors\Contracts\ColorsRepositoryInterface;
use App\Features\General\Colors\Models\Color;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class ShowColorBusinessTest extends UnitBaseTestCase
{
    private MockObject|ColorsRepositoryInterface $colorsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->colorsRepositoryMock = $this->createMock(ColorsRepositoryInterface::class);
    }

    public function getShowColorBusiness(): ShowColorBusiness
    {
        return new ShowColorBusiness($this->colorsRepositoryMock);
    }

    public function test_should_to_list_unique_color(): void
    {
        $showColorBusiness = $this->getShowColorBusiness();

        $showColorBusiness->setPolicy(
            new Policy([RulesEnum::COLORS_VIEW->value])
        );

        $this
            ->colorsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Color::ID => Uuid::uuid4Generate()]));

        $result = $showColorBusiness->handle(Uuid::uuid4Generate());

        $this->assertIsObject($result);
    }

    public function test_should_return_exception_if_color_not_exists(): void
    {
        $showColorBusiness = $this->getShowColorBusiness();

        $showColorBusiness->setPolicy(
            new Policy([RulesEnum::COLORS_VIEW->value])
        );

        $this
            ->colorsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::COLOR_NOT_FOUND));

        $showColorBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showColorBusiness = $this->getShowColorBusiness();

        $showColorBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showColorBusiness->handle(Uuid::uuid4Generate());
    }
}
