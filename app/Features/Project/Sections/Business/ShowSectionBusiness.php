<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\Contracts\ShowSectionBusinessInterface;
use App\Features\Project\Sections\Validations\SectionsValidations;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;

class ShowSectionBusiness extends Business implements ShowSectionBusinessInterface
{
    public function __construct(
        private readonly SectionsRepositoryInterface $sectionsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): object
    {
        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::SECTIONS_ADMIN_MASTER_VIEW->value),
            $policy->haveRule(RulesEnum::SECTIONS_PROJECT_MANAGER_VIEW->value),
                => $this->findByAdminMasterAndProjectManager($id),

            $policy->haveRule(RulesEnum::SECTIONS_TEAM_LEADER_VIEW->value),
            $policy->haveRule(RulesEnum::SECTIONS_PROJECT_MEMBER_VIEW->value)
                => $this->findByTeamLeaderAndProjectMember($id),

            default => $policy->dispatchForbiddenError()
        };
    }

    /**
     * @throws AppException
     */
    private function findByAdminMasterAndProjectManager(string $id): object
    {
        return SectionsValidations::sectionExists(
            $id,
            $this->sectionsRepository,
        );
    }

    /**
     * @throws AppException
     */
    private function findByTeamLeaderAndProjectMember(string $id): object
    {
        $result = SectionsValidations::sectionExists(
            $id,
            $this->sectionsRepository,
        );

        $this->canAccessProjects(
            [$result->project_id],
            MessagesEnum::SECTION_NOT_ALLOWED->value
        );

        return $result;
    }
}
