<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Project\Projects\Business\ShowProjectBusiness;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Models\Project;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class ShowProjectBusinessTest extends UnitBaseTestCase
{
    private MockObject|ProjectsRepositoryInterface $projectsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectsRepositoryMock = $this->createMock(ProjectsRepositoryInterface::class);
    }

    public function getShowProjectBusiness(): ShowProjectBusiness
    {
        return new ShowProjectBusiness(
            $this->projectsRepositoryMock,
        );
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::PROJECTS_ADMIN_MASTER_VIEW->value],
            'Project Manager' => [RulesEnum::PROJECTS_PROJECT_MANAGER_VIEW->value],
            'Team Leader'     => [RulesEnum::PROJECTS_TEAM_LEADER_VIEW->value],
            'Project Member'  => [RulesEnum::PROJECTS_PROJECT_MEMBER_VIEW->value],
        ];
    }

    public static function dataProviderValidateAccess(): array
    {
        return [
            'Team Leader'     => [RulesEnum::PROJECTS_TEAM_LEADER_VIEW->value],
            'Project Member'  => [RulesEnum::PROJECTS_PROJECT_MEMBER_VIEW->value],
        ];
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_unique_project(
        string $rule
    ): void
    {
        $showProjectBusiness = $this->getShowProjectBusiness();

        $showProjectBusiness->setPolicy(
            new Policy([$rule])
        );

        $authUserMock = $this->getAuthUserMock();

        $showProjectBusiness->setAuthenticatedUser($authUserMock);

        $project = $authUserMock->teamUser->getProjects()->first();

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn($project);

        $result = $showProjectBusiness->handle($project->id);

        $this->assertIsObject($result);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_project_not_found(
        string $rule
    ): void
    {
        $showProjectBusiness = $this->getShowProjectBusiness();

        $showProjectBusiness->setPolicy(
            new Policy([$rule])
        );

        $authUserMock = $this->getAuthUserMock();

        $showProjectBusiness->setAuthenticatedUser($authUserMock);

        $project = $authUserMock->teamUser->getProjects()->first();

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_FOUND));

        $showProjectBusiness->handle($project->id);
    }

    #[DataProvider('dataProviderValidateAccess')]
    public function test_must_return_exception_if_the_authenticated_user_does_not_have_access_to_the_project(
        string $rule,
    ): void
    {
        $showProjectBusiness = $this->getShowProjectBusiness();

        $showProjectBusiness->setPolicy(
            new Policy([$rule])
        );

        $showProjectBusiness->setAuthenticatedUser($this->getAuthUserMock());

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Project::ID => Uuid::uuid4Generate()]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_ALLOWED));

        $showProjectBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showProjectBusiness = $this->getShowProjectBusiness();

        $showProjectBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showProjectBusiness->handle(Uuid::uuid4Generate());
    }
}
