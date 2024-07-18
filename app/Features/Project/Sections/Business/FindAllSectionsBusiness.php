<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Features\Project\Sections\Contracts\FindAllSectionsBusinessInterface;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\DTO\SectionsFiltersDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class FindAllSectionsBusiness extends Business implements FindAllSectionsBusinessInterface
{
    private SectionsFiltersDTO $sectionsFiltersDTO;
    private ?object $project;

    public function __construct(
        private readonly SectionsRepositoryInterface $sectionsRepository,
        private readonly ProjectsRepositoryInterface $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(SectionsFiltersDTO $sectionsFiltersDTO): Collection
    {
        $this->sectionsFiltersDTO = $sectionsFiltersDTO;

        $this->handleValidateParams();

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::SECTIONS_ADMIN_MASTER_VIEW->value)
                => $this->findByAdminMaster(),

            $policy->haveRule(RulesEnum::SECTIONS_PROJECT_MANAGER_VIEW->value),
            $policy->haveRule(RulesEnum::SECTIONS_TEAM_LEADER_VIEW->value),
            $policy->haveRule(RulesEnum::SECTIONS_PROJECT_MEMBER_VIEW->value)
                => $this->findByProfileRule(),

            default => $policy->dispatchForbiddenError()
        };
    }

    private function findByAdminMaster(): Collection
    {
        return $this->sectionsRepository->findAll($this->sectionsFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findByProfileRule(): Collection
    {
        if(isset($this->sectionsFiltersDTO->projectId))
        {
            $this->project = ProjectsValidations::projectExists(
                $this->sectionsFiltersDTO->projectId,
                $this->projectsRepository
            );
        }

        if($this->sectionsFiltersDTO->projectUniqueName)
        {
            $this->project = ProjectsValidations::projectExistsByUniqueName(
                $this->sectionsFiltersDTO->projectUniqueName,
                $this->projectsRepository
            );
        }

        $this->canAccessProjects(
            [$this->project->id],
            MessagesEnum::PROJECT_NOT_ALLOWED_IN_SECTION->value
        );

        return $this->sectionsRepository->findAll($this->sectionsFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function handleValidateParams(): void
    {
        if(!isset($this->sectionsFiltersDTO->projectId) && !isset($this->sectionsFiltersDTO->projectUniqueName))
        {
            throw new AppException(
                MessagesEnum::PROJECT_ID_OR_PROJECT_UNIQUE_NAME_REQUIRED->value,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
