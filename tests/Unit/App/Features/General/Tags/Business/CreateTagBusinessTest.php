<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Tags\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Tags\Business\CreateTagBusiness;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\DTO\TagsDTO;
use App\Features\General\Tags\Models\Tag;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class CreateTagBusinessTest extends UnitBaseTestCase
{
    private MockObject|TagsRepositoryInterface $tagsRepositoryMock;
    private MockObject|TagsDTO $tagsDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tagsRepositoryMock = $this->createMock(TagsRepositoryInterface::class);
        $this->tagsDtoMock        = $this->createMock(TagsDTO::class);
    }

    public function getCreateTagBusiness(): CreateTagBusiness
    {
        return new CreateTagBusiness($this->tagsRepositoryMock);
    }

    public function test_should_create_new_tag()
    {
        $createTagBusiness = $this->getCreateTagBusiness();

        $createTagBusiness->setPolicy(
            new Policy([RulesEnum::TAGS_INSERT->value])
        );

        $this
            ->tagsRepositoryMock
            ->method('create')
            ->willReturn((object) ([Tag::ID => Uuid::uuid4Generate()]));

        $result = $createTagBusiness->handle($this->tagsDtoMock);

        $this->assertIsObject($result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createTagBusiness = $this->getCreateTagBusiness();

        $createTagBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createTagBusiness->handle($this->tagsDtoMock);
    }
}
