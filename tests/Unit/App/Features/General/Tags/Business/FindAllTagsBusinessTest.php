<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Tags\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Tags\Business\FindAllTagsBusiness;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class FindAllTagsBusinessTest extends UnitBaseTestCase
{
    private MockObject|TagsRepositoryInterface $tagsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tagsRepositoryMock = $this->createMock(TagsRepositoryInterface::class);
    }

    public function getFindAllTagsBusiness(): FindAllTagsBusiness
    {
        return new FindAllTagsBusiness($this->tagsRepositoryMock);
    }

    public function test_should_find_all_tags(): void
    {
        $findAllTagsBusiness = $this->getFindAllTagsBusiness();

        $findAllTagsBusiness->setPolicy(
            new Policy([RulesEnum::TAGS_VIEW->value])
        );

        $this
            ->tagsRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::make());

        $result = $findAllTagsBusiness->handle();

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllTagsBusiness = $this->getFindAllTagsBusiness();

        $findAllTagsBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllTagsBusiness->handle();
    }
}
