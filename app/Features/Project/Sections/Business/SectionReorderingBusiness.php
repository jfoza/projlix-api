<?php

namespace App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Sections\Contracts\SectionReorderingBusinessInterface;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\Validations\SectionsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class SectionReorderingBusiness extends Business implements SectionReorderingBusinessInterface
{
    private string $sectionId;
    private int $newOrder;
    private string $projectId;

    public function __construct(
        private readonly SectionsRepositoryInterface $sectionsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $sectionId, int $newOrder): void
    {
        $this->sectionId = $sectionId;
        $this->newOrder  = $newOrder;

        $policy = $this->getPolicy();

        match (true)
        {
            $policy->haveRule(RulesEnum::SECTIONS_ORDER_ADMIN_MASTER_UPDATE->value)
                => $this->updateByAdminMaster(),

            $policy->haveRule(RulesEnum::SECTIONS_ORDER_PROJECT_MANAGER_UPDATE->value),
            $policy->haveRule(RulesEnum::SECTIONS_ORDER_TEAM_LEADER_UPDATE->value),
            $policy->haveRule(RulesEnum::SECTIONS_ORDER_PROJECT_MEMBER_UPDATE->value)
                => $this->updateByProfileRule(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): void
    {
        $this->handleValidations();

        $this->sectionsRepository->reorderSection(
            $this->sectionId,
            $this->newOrder,
            $this->projectId,
        );
    }

    /**
     * @throws AppException
     */
    private function updateByProfileRule(): void
    {
        $this->handleValidations();

        $this->canAccessProjects([$this->projectId]);

        Transaction::beginTransaction();

        try
        {
            $this->sectionsRepository->reorderSection(
                $this->sectionId,
                $this->newOrder,
                $this->projectId,
            );

            Transaction::commit();
        }
        catch (\Exception $exception)
        {
            Transaction::rollBack();

            AppException::dispatchByEnvironment($exception);
        }
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        $section = SectionsValidations::sectionExists(
            $this->sectionId,
            $this->sectionsRepository
        );

        $this->projectId = $section->project->id;
    }
}
