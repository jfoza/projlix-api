<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Colors\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Colors\Business\FindAllColorsBusiness;
use App\Features\General\Colors\Contracts\ColorsRepositoryInterface;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class FindAllColorsBusinessTest extends UnitBaseTestCase
{
    private MockObject|ColorsRepositoryInterface $colorsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->colorsRepositoryMock = $this->createMock(ColorsRepositoryInterface::class);
    }

    public function getFindAllColorsBusiness(): FindAllColorsBusiness
    {
        return new FindAllColorsBusiness($this->colorsRepositoryMock);
    }

    public function test_should_find_all_colors(): void
    {
        $findAllColorsBusiness = $this->getFindAllColorsBusiness();

        $findAllColorsBusiness->setPolicy(
            new Policy([RulesEnum::COLORS_VIEW->value])
        );

        $this
            ->colorsRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $result = $findAllColorsBusiness->handle();

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllColorsBusiness = $this->getFindAllColorsBusiness();

        $findAllColorsBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllColorsBusiness->handle();
    }
}
