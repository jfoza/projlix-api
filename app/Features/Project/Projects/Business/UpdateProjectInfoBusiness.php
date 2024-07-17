<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectInfoBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateProjectInfoBusiness extends Business implements UpdateProjectInfoBusinessInterface
{
    private ProjectDTO $projectDTO;

    public function __construct(
        private readonly ProjectsRepositoryInterface $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(ProjectDTO $projectDTO): object
    {
        $this->projectDTO = $projectDTO;

        $policy = $this->getPolicy();

        match (true)
        {
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_INFO_UPDATE->value) => true,

            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_INFO_UPDATE->value),
            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_INFO_UPDATE->value) => function() {
                $this->canAccessProjects([$this->projectDTO->id]);
            },

            default => $policy->dispatchForbiddenError(),
        };

        return $this->handleUpdateProjectInfo();
    }

    /**
     * @throws AppException
     */
    private function handleUpdateProjectInfo(): object
    {
        ProjectsValidations::projectExists(
            $this->projectDTO->id,
            $this->projectsRepository
        );

        ProjectsValidations::projectExistsByNameInUpdate(
            $this->projectDTO->id,
            $this->projectDTO->name,
            $this->projectsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $result = $this->projectsRepository->save($this->projectDTO);

            Transaction::commit();

            return $result;
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
