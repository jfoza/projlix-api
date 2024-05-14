<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Colors\Contracts\ColorsRepositoryInterface;
use App\Features\General\Colors\Validations\ColorsValidations;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\General\Icons\Validations\IconsValidations;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\Contracts\UpdateSectionBusinessInterface;
use App\Features\Project\Sections\DTO\SectionsDTO;
use App\Features\Project\Sections\Validations\SectionsValidations;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateSectionBusiness extends Business implements UpdateSectionBusinessInterface
{
    private SectionsDTO $sectionsDTO;
    private object $section;

    public function __construct(
        private readonly SectionsRepositoryInterface $sectionsRepository,
        private readonly ColorsRepositoryInterface   $colorsRepository,
        private readonly IconsRepositoryInterface    $iconsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(SectionsDTO $sectionsDTO): object
    {
        $this->sectionsDTO = $sectionsDTO;

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::SECTIONS_ADMIN_MASTER_UPDATE->value),
            $policy->haveRule(RulesEnum::SECTIONS_PROJECT_MANAGER_UPDATE->value)
                => $this->updateByAdminMasterAndProjectManager(),

            $policy->haveRule(RulesEnum::SECTIONS_TEAM_LEADER_UPDATE->value) => $this->updateByTeamLeader(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMasterAndProjectManager(): object
    {
        $this->handleValidations();

        return $this->updateSection();
    }

    /**
     * @throws AppException
     */
    private function updateByTeamLeader(): object
    {
        $this->handleValidations();

        $this->canAccessProjects(
            [$this->section->project_id],
            MessagesEnum::SECTION_NOT_ALLOWED->value
        );

        return $this->updateSection();
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        $this->section = SectionsValidations::sectionExists(
            $this->sectionsDTO->id,
            $this->sectionsRepository,
        );

        ColorsValidations::colorExists(
            $this->sectionsDTO->colorId,
            $this->colorsRepository,
        );

        IconsValidations::iconExists(
            $this->sectionsDTO->iconId,
            $this->iconsRepository,
        );
    }

    /**
     * @throws AppException
     */
    private function updateSection(): object
    {
        Transaction::beginTransaction();

        try
        {
            $result = $this->sectionsRepository->save($this->sectionsDTO);

            Transaction::commit();

            return $result;
        }
        catch (\Exception $exception)
        {
            Transaction::rollBack();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
