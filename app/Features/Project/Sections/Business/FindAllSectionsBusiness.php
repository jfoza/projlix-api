<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Sections\Contracts\FindAllSectionsBusinessInterface;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\DTO\SectionsFiltersDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;

class FindAllSectionsBusiness extends Business implements FindAllSectionsBusinessInterface
{
    private SectionsFiltersDTO $sectionsFiltersDTO;

    public function __construct(
        private readonly SectionsRepositoryInterface $sectionsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(SectionsFiltersDTO $sectionsFiltersDTO): Collection
    {
        $this->sectionsFiltersDTO = $sectionsFiltersDTO;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::SECTIONS_ADMIN_MASTER_VIEW->value),
            $policy->haveRule(RulesEnum::SECTIONS_PROJECT_MANAGER_VIEW->value),
                => $this->findByAdminMasterAndProjectManager(),

            $policy->haveRule(RulesEnum::SECTIONS_TEAM_LEADER_VIEW->value),
            $policy->haveRule(RulesEnum::SECTIONS_PROJECT_MEMBER_VIEW->value)
                => $this->findByTeamLeaderAndProjectMember(),

            default => $policy->dispatchForbiddenError()
        };
    }

    private function findByAdminMasterAndProjectManager(): Collection
    {
        return $this->sectionsRepository->findAll($this->sectionsFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findByTeamLeaderAndProjectMember(): Collection
    {
        $this->canAccessProjects(
            [$this->sectionsFiltersDTO->projectId],
            MessagesEnum::PROJECT_NOT_ALLOWED_IN_SECTION->value
        );

        return $this->sectionsRepository->findAll($this->sectionsFiltersDTO);
    }
}
