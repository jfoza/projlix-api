<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Tags\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Tags\Business\UpdateStatusTagBusiness;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\DTO\TagsDTO;
use App\Features\General\Tags\Models\Tag;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class UpdateStatusTagBusinessTest extends UnitBaseTestCase
{
    private MockObject|TagsRepositoryInterface $tagsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tagsRepositoryMock = $this->createMock(TagsRepositoryInterface::class);
    }

    public function getUpdateStatusTagBusiness(): UpdateStatusTagBusiness
    {
        return new UpdateStatusTagBusiness($this->tagsRepositoryMock);
    }

    public function test_should_update_status_tag()
    {
        $updateStatusTagBusiness = $this->getUpdateStatusTagBusiness();

        $updateStatusTagBusiness->setPolicy(
            new Policy([RulesEnum::TAGS_UPDATE->value])
        );

        $this
            ->tagsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Tag::ID => Uuid::uuid4Generate(),
                Tag::ACTIVE => true,
            ]));

        $result = $updateStatusTagBusiness->handle(Uuid::uuid4Generate());

        $this->assertIsObject($result);
    }

    public function test_should_return_exception_if_tag_not_exists(): void
    {
        $updateStatusTagBusiness = $this->getUpdateStatusTagBusiness();

        $updateStatusTagBusiness->setPolicy(
            new Policy([RulesEnum::TAGS_UPDATE->value])
        );

        $this
            ->tagsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::TAG_NOT_FOUND));

        $updateStatusTagBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateStatusTagBusiness = $this->getUpdateStatusTagBusiness();

        $updateStatusTagBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateStatusTagBusiness->handle(Uuid::uuid4Generate());
    }
}
