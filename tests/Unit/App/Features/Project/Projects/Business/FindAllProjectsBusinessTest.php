<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Base\Pagination\PaginationOrder;
use App\Features\Project\Projects\Business\FindAllProjectsBusiness;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\DTO\ProjectsFiltersDTO;
use App\Features\Project\Projects\Models\Project;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class FindAllProjectsBusinessTest extends UnitBaseTestCase
{
    private MockObject|ProjectsRepositoryInterface $projectsRepositoryMock;
    private MockObject|ProjectsFiltersDTO $projectsFiltersDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectsRepositoryMock = $this->createMock(ProjectsRepositoryInterface::class);
        $this->projectsFiltersDtoMock = $this->createMock(ProjectsFiltersDTO::class);
    }

    public function getFindAllProjectsBusiness(): FindAllProjectsBusiness
    {
        return new FindAllProjectsBusiness(
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

    public function getProjects(): Collection
    {
        return Collection::make([
            [Project::ID]
        ]);
    }

    public function getPaginatedUsersList(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $this->getProjects(),
            10,
            10,
        );
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_projects_list(
        string $rule
    ): void
    {
        $findAllProjectsBusiness = $this->getFindAllProjectsBusiness();

        $findAllProjectsBusiness->setPolicy(
            new Policy([$rule])
        );

        $findAllProjectsBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this
            ->projectsRepositoryMock
            ->method('findAll')
            ->willReturn($this->getProjects());

        $result = $findAllProjectsBusiness->handle($this->projectsFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $result);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_paginated_projects_list(
        string $rule,
    ): void
    {
        $findAllProjectsBusiness = $this->getFindAllProjectsBusiness();

        $findAllProjectsBusiness->setPolicy(
            new Policy([$rule])
        );

        $findAllProjectsBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this->projectsFiltersDtoMock->paginationOrder = new PaginationOrder();

        $this->projectsFiltersDtoMock->paginationOrder->setPage(1);
        $this->projectsFiltersDtoMock->paginationOrder->setPerPage(10);

        $this
            ->projectsRepositoryMock
            ->method('findAll')
            ->willReturn($this->getPaginatedUsersList());

        $result = $findAllProjectsBusiness->handle($this->projectsFiltersDtoMock);

        $this->assertInstanceOf(LengthAwarePaginatorContract::class, $result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllProjectsBusiness = $this->getFindAllProjectsBusiness();

        $findAllProjectsBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllProjectsBusiness->handle($this->projectsFiltersDtoMock);
    }
}
