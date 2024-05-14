<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Icons\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Icons\Business\FindAllIconsBusiness;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\General\Icons\Repositories\IconsRepository;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class FindAllIconsBusinessTest extends UnitBaseTestCase
{
    private MockObject|IconsRepositoryInterface $iconsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->iconsRepositoryMock = $this->createMock(IconsRepository::class);
    }

    public function getFindAllIconsBusiness(): FindAllIconsBusiness
    {
        return new FindAllIconsBusiness($this->iconsRepositoryMock);
    }

    public function test_should_find_all_icons(): void
    {
        $findAllIconsBusiness = $this->getFindAllIconsBusiness();

        $findAllIconsBusiness->setPolicy(
            new Policy([RulesEnum::ICONS_VIEW->value])
        );

        $this
            ->iconsRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $result = $findAllIconsBusiness->handle();

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllIconsBusiness = $this->getFindAllIconsBusiness();

        $findAllIconsBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllIconsBusiness->handle();
    }
}
