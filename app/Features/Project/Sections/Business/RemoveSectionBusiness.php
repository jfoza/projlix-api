<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Sections\Contracts\RemoveSectionBusinessInterface;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\Validations\SectionsValidations;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveSectionBusiness extends Business implements RemoveSectionBusinessInterface
{
    private string $id;

    public function __construct(
        private readonly SectionsRepositoryInterface $sectionsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): void
    {
        $this->id = $id;

        $policy = $this->getPolicy();

        match (true) {
            $policy->haveRule(RulesEnum::SECTIONS_ADMIN_MASTER_DELETE->value),
            $policy->haveRule(RulesEnum::SECTIONS_PROJECT_MANAGER_DELETE->value),
                => $this->removeByAdminMasterAndProjectManager(),

            $policy->haveRule(RulesEnum::SECTIONS_TEAM_LEADER_DELETE->value),
                => $this->removeByTeamLeader(),

            default => $policy->dispatchForbiddenError()
        };
    }

    /**
     * @throws AppException
     */
    private function removeByAdminMasterAndProjectManager(): void
    {
        SectionsValidations::sectionExists(
            $this->id,
            $this->sectionsRepository,
        );

        $this->sectionsRepository->remove($this->id);
    }

    /**
     * @throws AppException
     */
    private function removeByTeamLeader(): void
    {
        $result = SectionsValidations::sectionExists(
            $this->id,
            $this->sectionsRepository,
        );

        $this->canAccessProjects(
            [$result->project_id],
            MessagesEnum::SECTION_NOT_ALLOWED->value
        );

        Transaction::beginTransaction();

        try
        {
            $this->sectionsRepository->remove($this->id);

            Transaction::commit();
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
