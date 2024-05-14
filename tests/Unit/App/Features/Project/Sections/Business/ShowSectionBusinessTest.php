<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Project\Projects\Models\Project;
use App\Features\Project\Sections\Business\ShowSectionBusiness;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\Models\Section;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class ShowSectionBusinessTest extends UnitBaseTestCase
{
    private MockObject|SectionsRepositoryInterface $sectionsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sectionsRepositoryMock = $this->createMock(SectionsRepositoryInterface::class);
    }

    public function getShowSectionBusiness(): ShowSectionBusiness
    {
        return new ShowSectionBusiness($this->sectionsRepositoryMock);
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::SECTIONS_ADMIN_MASTER_VIEW->value],
            'Project Manager' => [RulesEnum::SECTIONS_PROJECT_MANAGER_VIEW->value],
            'Team Leader'     => [RulesEnum::SECTIONS_TEAM_LEADER_VIEW->value],
            'Project Member'  => [RulesEnum::SECTIONS_PROJECT_MEMBER_VIEW->value],
        ];
    }

    public static function dataProviderValidationRules(): array
    {
        return [
            'Team Leader'     => [RulesEnum::SECTIONS_TEAM_LEADER_VIEW->value],
            'Project Member'  => [RulesEnum::SECTIONS_PROJECT_MEMBER_VIEW->value],
        ];
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_find_section(
        string $rule,
    ): void
    {
        $showSectionBusiness = $this->getShowSectionBusiness();

        $showSectionBusiness->setPolicy(
            new Policy([$rule])
        );

        $project = (object) ([
            Project::ID => Uuid::uuid4Generate(),
        ]);

        $authUserMock = $this->getAuthUserMock();

        $authUserMock->teamUser->setProjects(Collection::make([
            $project,
            (object) ([
                Project::ID => Uuid::uuid4Generate(),
            ])
        ]));

        $showSectionBusiness->setAuthenticatedUser($authUserMock);

        $this
            ->sectionsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Section::ID,
                Section::PROJECT_ID => $project->id
            ]));

        $result = $showSectionBusiness->handle(Uuid::uuid4Generate());

        $this->assertIsObject($result);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_section_not_found(
        string $rule,
    ): void
    {
        $showSectionBusiness = $this->getShowSectionBusiness();

        $showSectionBusiness->setPolicy(
            new Policy([$rule])
        );

        $showSectionBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this
            ->sectionsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SECTION_NOT_FOUND));

        $showSectionBusiness->handle(Uuid::uuid4Generate());
    }

    #[DataProvider('dataProviderValidationRules')]
    public function test_must_return_exception_if_the_authenticated_user_does_not_have_access_to_the_project(
        string $rule,
    ): void
    {
        $showSectionBusiness = $this->getShowSectionBusiness();

        $showSectionBusiness->setPolicy(
            new Policy([$rule])
        );

        $showSectionBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this
            ->sectionsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Section::ID,
                Section::PROJECT_ID => Uuid::uuid4Generate()
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SECTION_NOT_ALLOWED));

        $showSectionBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showSectionBusiness = $this->getShowSectionBusiness();

        $showSectionBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showSectionBusiness->handle(Uuid::uuid4Generate());
    }
}
