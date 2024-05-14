<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Project\Projects\Business\RemoveProjectBusiness;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Models\Project;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class RemoveProjectBusinessTest extends UnitBaseTestCase
{
    private MockObject|ProjectsRepositoryInterface $projectsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectsRepositoryMock = $this->createMock(ProjectsRepositoryInterface::class);
    }

    public function getRemoveProjectBusiness(): RemoveProjectBusiness
    {
        return new RemoveProjectBusiness(
            $this->projectsRepositoryMock,
        );
    }

    public function test_should_remove_unique_project()
    {
        $removeProjectBusiness = $this->getRemoveProjectBusiness();

        $removeProjectBusiness->setPolicy(
            new Policy([RulesEnum::PROJECTS_ADMIN_MASTER_DELETE->value])
        );

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Project::ID => Uuid::uuid4Generate()]));

        $removeProjectBusiness->handle(Uuid::uuid4Generate());

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_project_not_found()
    {
        $removeProjectBusiness = $this->getRemoveProjectBusiness();

        $removeProjectBusiness->setPolicy(
            new Policy([RulesEnum::PROJECTS_ADMIN_MASTER_DELETE->value])
        );

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_FOUND));

        $removeProjectBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeProjectBusiness = $this->getRemoveProjectBusiness();

        $removeProjectBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeProjectBusiness->handle(Uuid::uuid4Generate());
    }
}
