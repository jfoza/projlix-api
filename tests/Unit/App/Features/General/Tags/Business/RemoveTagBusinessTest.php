<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\General\Tags\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Tags\Business\RemoveTagBusiness;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\Models\Tag;
use App\Features\Project\Projects\Models\Project;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class RemoveTagBusinessTest extends UnitBaseTestCase
{
    private MockObject|TagsRepositoryInterface $tagsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tagsRepositoryMock = $this->createMock(TagsRepositoryInterface::class);
    }

    public function getRemoveTagBusiness(): RemoveTagBusiness
    {
        return new RemoveTagBusiness($this->tagsRepositoryMock);
    }

    public function test_should_remove_tag()
    {
        $removeTagBusiness = $this->getRemoveTagBusiness();

        $removeTagBusiness->setPolicy(
            new Policy([RulesEnum::TAGS_DELETE->value])
        );

        $this
            ->tagsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Tag::ID => Uuid::uuid4Generate(),
                Tag::ACTIVE => true,
                'projects' => Collection::empty()
            ]));

        $removeTagBusiness->handle(Uuid::uuid4Generate());

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_tag_not_exists()
    {
        $removeTagBusiness = $this->getRemoveTagBusiness();

        $removeTagBusiness->setPolicy(
            new Policy([RulesEnum::TAGS_DELETE->value])
        );

        $this
            ->tagsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::TAG_NOT_FOUND));

        $removeTagBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_tag_has_projects()
    {
        $removeTagBusiness = $this->getRemoveTagBusiness();

        $removeTagBusiness->setPolicy(
            new Policy([RulesEnum::TAGS_DELETE->value])
        );

        $this
            ->tagsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Tag::ID => Uuid::uuid4Generate(),
                Tag::ACTIVE => true,
                'projects' => Collection::make([[Project::ID => Uuid::uuid4Generate()]])
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::TAG_HAS_PROJECTS_IN_DELETE));

        $removeTagBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeTagBusiness = $this->getRemoveTagBusiness();

        $removeTagBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeTagBusiness->handle(Uuid::uuid4Generate());
    }
}
