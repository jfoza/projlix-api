<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\Project\Projects\Models\Project;
use App\Features\Project\Sections\Business\RemoveSectionBusiness;
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

class RemoveSectionBusinessTest extends UnitBaseTestCase
{
    private MockObject|SectionsRepositoryInterface $sectionsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sectionsRepositoryMock = $this->createMock(SectionsRepositoryInterface::class);
    }

    public function getRemoveSectionBusiness(): RemoveSectionBusiness
    {
        return new RemoveSectionBusiness($this->sectionsRepositoryMock);
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::SECTIONS_ADMIN_MASTER_DELETE->value],
            'Project Manager' => [RulesEnum::SECTIONS_PROJECT_MANAGER_DELETE->value],
            'Team Leader'     => [RulesEnum::SECTIONS_TEAM_LEADER_DELETE->value],
        ];
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_remove_section(
        string $rule,
    ): void
    {
        $removeSectionBusiness = $this->getRemoveSectionBusiness();

        $removeSectionBusiness->setPolicy(
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

        $removeSectionBusiness->setAuthenticatedUser($authUserMock);

        $this
            ->sectionsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([
                Section::ID,
                Section::PROJECT_ID => $project->id
            ]));

        $removeSectionBusiness->handle(Uuid::uuid4Generate());

        $this->assertTrue(true);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_section_not_found(
        string $rule,
    ): void
    {
        $removeSectionBusiness = $this->getRemoveSectionBusiness();

        $removeSectionBusiness->setPolicy(
            new Policy([$rule])
        );

        $removeSectionBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this
            ->sectionsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SECTION_NOT_FOUND));

        $removeSectionBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_must_return_exception_if_the_authenticated_user_does_not_have_access_to_the_project()
    {
        $removeSectionBusiness = $this->getRemoveSectionBusiness();

        $removeSectionBusiness->setPolicy(
            new Policy([RulesEnum::SECTIONS_TEAM_LEADER_DELETE->value])
        );

        $removeSectionBusiness->setAuthenticatedUser(
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

        $removeSectionBusiness->handle(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeSectionBusiness = $this->getRemoveSectionBusiness();

        $removeSectionBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeSectionBusiness->handle(Uuid::uuid4Generate());
    }
}
