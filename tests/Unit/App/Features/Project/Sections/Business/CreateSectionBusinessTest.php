<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\ACL\Policy;
use App\Features\General\Colors\Contracts\ColorsRepositoryInterface;
use App\Features\General\Colors\Models\Color;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\General\Icons\Models\Icon;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Models\Project;
use App\Features\Project\Sections\Business\CreateSectionBusiness;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\DTO\SectionsDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\UnitBaseTestCase;

class CreateSectionBusinessTest extends UnitBaseTestCase
{
    private MockObject|SectionsRepositoryInterface $sectionsRepositoryMock;
    private MockObject|ProjectsRepositoryInterface $projectsRepositoryMock;
    private MockObject|ColorsRepositoryInterface   $colorsRepositoryMock;
    private MockObject|IconsRepositoryInterface    $iconsRepositoryMock;

    private MockObject|SectionsDTO $sectionsDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sectionsRepositoryMock = $this->createMock(SectionsRepositoryInterface::class);
        $this->projectsRepositoryMock = $this->createMock(ProjectsRepositoryInterface::class);
        $this->colorsRepositoryMock   = $this->createMock(ColorsRepositoryInterface::class);
        $this->iconsRepositoryMock    = $this->createMock(IconsRepositoryInterface::class);

        $this->sectionsDtoMock = $this->createMock(SectionsDTO::class);
    }

    public function getCreateSectionBusiness(): CreateSectionBusiness
    {
        return new CreateSectionBusiness(
            $this->sectionsRepositoryMock,
            $this->projectsRepositoryMock,
            $this->colorsRepositoryMock,
            $this->iconsRepositoryMock,
        );
    }

    public static function dataProviderRules(): array
    {
        return [
            'Admin Master'    => [RulesEnum::SECTIONS_ADMIN_MASTER_INSERT->value],
            'Project Manager' => [RulesEnum::SECTIONS_PROJECT_MANAGER_INSERT->value],
            'Team Leader'     => [RulesEnum::SECTIONS_TEAM_LEADER_INSERT->value],
        ];
    }

    public function setSectionDto(
        string $projectId,
        string $colorId,
        string $iconId,
        string $name,
    ): void
    {
        $this->sectionsDtoMock->projectId = $projectId;
        $this->sectionsDtoMock->colorId   = $colorId;
        $this->sectionsDtoMock->iconId    = $iconId;
        $this->sectionsDtoMock->name      = $name;
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_create_a_section(
        string $rule,
    ): void
    {
        $createSectionBusiness = $this->getCreateSectionBusiness();

        $createSectionBusiness->setPolicy(
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

        $createSectionBusiness->setAuthenticatedUser($authUserMock);

        $this->setSectionDto(
            $project->id,
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
            'test'
        );

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn($project);

        $this
            ->colorsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Color::ID => Uuid::uuid4Generate()]));

        $this
            ->iconsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Icon::ID => Uuid::uuid4Generate()]));

        $result = $createSectionBusiness->handle($this->sectionsDtoMock);

        $this->assertIsObject($result);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_project_not_found(
        string $rule,
    ): void
    {
        $createSectionBusiness = $this->getCreateSectionBusiness();

        $createSectionBusiness->setPolicy(
            new Policy([$rule])
        );

        $createSectionBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this->setSectionDto(
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
            'test'
        );

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_FOUND));

        $createSectionBusiness->handle($this->sectionsDtoMock);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_color_not_found(
        string $rule,
    ): void
    {
        $createSectionBusiness = $this->getCreateSectionBusiness();

        $createSectionBusiness->setPolicy(
            new Policy([$rule])
        );

        $createSectionBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this->setSectionDto(
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
            'test'
        );

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Project::ID => Uuid::uuid4Generate()]));

        $this
            ->colorsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::COLOR_NOT_FOUND));

        $createSectionBusiness->handle($this->sectionsDtoMock);
    }

    #[DataProvider('dataProviderRules')]
    public function test_should_return_exception_if_icon_not_found(
        string $rule,
    ): void
    {
        $createSectionBusiness = $this->getCreateSectionBusiness();

        $createSectionBusiness->setPolicy(
            new Policy([$rule])
        );

        $createSectionBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this->setSectionDto(
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
            'test'
        );

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Project::ID => Uuid::uuid4Generate()]));

        $this
            ->colorsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Color::ID => Uuid::uuid4Generate()]));

        $this
            ->iconsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::ICON_NOT_FOUND));

        $createSectionBusiness->handle($this->sectionsDtoMock);
    }

    public function test_must_return_exception_if_the_authenticated_user_does_not_have_access_to_the_project()
    {
        $createSectionBusiness = $this->getCreateSectionBusiness();

        $createSectionBusiness->setPolicy(
            new Policy([RulesEnum::SECTIONS_TEAM_LEADER_INSERT->value])
        );

        $createSectionBusiness->setAuthenticatedUser(
            $this->getAuthUserMock()
        );

        $this->setSectionDto(
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
            Uuid::uuid4Generate(),
            'test'
        );

        $this
            ->projectsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Project::ID => Uuid::uuid4Generate()]));

        $this
            ->colorsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Color::ID => Uuid::uuid4Generate()]));

        $this
            ->iconsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Icon::ID => Uuid::uuid4Generate()]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROJECT_NOT_ALLOWED));

        $createSectionBusiness->handle($this->sectionsDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createSectionBusiness = $this->getCreateSectionBusiness();

        $createSectionBusiness->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createSectionBusiness->handle($this->sectionsDtoMock);
    }
}
