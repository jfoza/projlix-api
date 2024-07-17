<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\General\Icons\Validations\IconsValidations;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectIconBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateProjectIconBusiness extends Business implements UpdateProjectIconBusinessInterface
{
    private ProjectDTO $projectDTO;

    public function __construct(
        private readonly IconsRepositoryInterface    $iconsRepository,
        private readonly ProjectsRepositoryInterface $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(ProjectDTO $projectDTO): void
    {
        $this->projectDTO = $projectDTO;

        $policy = $this->getPolicy();

        match (true)
        {
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_ICON_UPDATE->value) => true,

            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_ICON_UPDATE->value),
            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_ICON_UPDATE->value) => function() {
                $this->canAccessProjects([$this->projectDTO->id]);
            },

            default => $policy->dispatchForbiddenError(),
        };

        $this->handleUpdateProjectIcon();
    }

    /**
     * @throws AppException
     */
    private function handleUpdateProjectIcon(): void
    {
        ProjectsValidations::projectExists(
            $this->projectDTO->id,
            $this->projectsRepository
        );

        IconsValidations::iconExists(
            $this->projectDTO->iconId,
            $this->iconsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $this->projectsRepository->saveIcon(
                $this->projectDTO->id,
                $this->projectDTO->iconId,
            );

            Transaction::commit();
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
