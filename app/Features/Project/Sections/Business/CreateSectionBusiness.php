<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Colors\Contracts\ColorsRepositoryInterface;
use App\Features\General\Colors\Validations\ColorsValidations;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\General\Icons\Validations\IconsValidations;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Features\Project\Sections\Contracts\CreateSectionBusinessInterface;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\DTO\SectionsDTO;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateSectionBusiness extends Business implements CreateSectionBusinessInterface
{
    private SectionsDTO $sectionsDTO;

    public function __construct(
        private readonly SectionsRepositoryInterface $sectionsRepository,
        private readonly ProjectsRepositoryInterface $projectsRepository,
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
            $policy->haveRule(RulesEnum::SECTIONS_ADMIN_MASTER_INSERT->value),
            $policy->haveRule(RulesEnum::SECTIONS_PROJECT_MANAGER_INSERT->value)
                => $this->createByAdminMasterAndProjectManager(),

            $policy->haveRule(RulesEnum::SECTIONS_TEAM_LEADER_INSERT->value) => $this->createByTeamLeader(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function createByAdminMasterAndProjectManager(): object
    {
        $this->handleValidations();

        return $this->createSection();
    }

    /**
     * @throws AppException
     */
    private function createByTeamLeader(): object
    {
        $this->handleValidations();

        $this->canAccessProjects([$this->sectionsDTO->projectId]);

        return $this->createSection();
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        ProjectsValidations::projectExists(
            $this->sectionsDTO->projectId,
            $this->projectsRepository,
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
    private function createSection()
    {
        Transaction::beginTransaction();

        try
        {
            $result = $this->sectionsRepository->create($this->sectionsDTO);

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
