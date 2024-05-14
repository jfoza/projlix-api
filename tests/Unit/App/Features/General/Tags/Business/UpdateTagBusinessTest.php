<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Tags\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Tags\Business\UpdateTagBusiness;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\DTO\TagsDTO;
use App\Features\General\Tags\Models\Tag;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class UpdateTagBusinessTest extends UnitBaseTestCase
{
    private MockObject|TagsRepositoryInterface $tagsRepositoryMock;
    private MockObject|TagsDTO $tagsDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tagsRepositoryMock = $this->createMock(TagsRepositoryInterface::class);
        $this->tagsDtoMock        = $this->createMock(TagsDTO::class);

        $this->tagsDtoMock->id = Uuid::uuid4Generate();
    }

    public function getUpdateTagBusiness(): UpdateTagBusiness
    {
        return new UpdateTagBusiness($this->tagsRepositoryMock);
    }

    public function test_should_update_tag()
    {
        $updateTagBusiness = $this->getUpdateTagBusiness();

        $updateTagBusiness->setPolicy(
            new Policy([RulesEnum::TAGS_UPDATE->value])
        );

        $this
            ->tagsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Tag::ID => Uuid::uuid4Generate()]));

        $this
            ->tagsRepositoryMock
            ->method('save')
            ->willReturn((object) ([Tag::ID => Uuid::uuid4Generate()]));

        $result = $updateTagBusiness->handle($this->tagsDtoMock);

        $this->assertIsObject($result);
    }

    public function test_should_return_exception_if_tag_not_exists(): void
    {
        $updateTagBusiness = $this->getUpdateTagBusiness();

        $updateTagBusiness->setPolicy(
            new Policy([RulesEnum::TAGS_UPDATE->value])
        );

        $this
            ->tagsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::TAG_NOT_FOUND));

        $updateTagBusiness->handle($this->tagsDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateTagBusiness = $this->getUpdateTagBusiness();

        $updateTagBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateTagBusiness->handle($this->tagsDtoMock);
    }
}
