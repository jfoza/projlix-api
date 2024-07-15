<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\Project\Projects\Contracts\CreateProjectBusinessInterface;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Responses\SavedProjectsResponse;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\Validations\TeamUsersValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Transaction;
use Illuminate\Support\Collection;

class CreateProjectBusiness extends Business implements CreateProjectBusinessInterface
{
    private ProjectDTO $projectDTO;
    private Collection $teamUsers;

    public function __construct(
        private readonly ProjectsRepositoryInterface  $projectsRepository,
        private readonly TeamUsersRepositoryInterface $teamUsersRepository,
        private readonly IconsRepositoryInterface     $iconsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(ProjectDTO $projectDTO): SavedProjectsResponse
    {
        $this->projectDTO = $projectDTO;
        $this->teamUsers = Collection::empty();

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_INSERT->value),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_INSERT->value)
                => $this->createByAdminMasterAndProjectManager(),

            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_INSERT->value) => $this->createByTeamLeader(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function createByAdminMasterAndProjectManager(): SavedProjectsResponse
    {
        $this->handleValidations();

        Transaction::beginTransaction();

        try
        {
            $this->handlePopulateDefaultData();

            $projectCreated = $this->projectsRepository->create($this->projectDTO);

            if(isset($this->projectDTO->teamUsers))
            {
                $this->projectsRepository->saveTeamUsers($projectCreated->id, $this->projectDTO->teamUsers);
            }

            Transaction::commit();

            return SavedProjectsResponse::setUp(
                $projectCreated->id,
                $projectCreated->name,
                $projectCreated->description,
                $this->teamUsers
            );
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }

    /**
     * @throws AppException
     */
    private function createByTeamLeader(): SavedProjectsResponse
    {
        $this->handleValidations();

        Transaction::beginTransaction();

        try
        {
            $this->handlePopulateDefaultData();

            $projectCreated = $this->projectsRepository->create($this->projectDTO);

            $teamUsersIds = [$this->getTeamUserId()];

            $teamUsersIds = array_merge($teamUsersIds, $this->projectDTO->teamUsers);

            $this->projectsRepository->saveTeamUsers($projectCreated->id, $teamUsersIds);

            Transaction::commit();

            return SavedProjectsResponse::setUp(
                $projectCreated->id,
                $projectCreated->name,
                $projectCreated->description,
                $this->teamUsers
            );
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        ProjectsValidations::projectExistsByName(
            $this->projectDTO->name,
            $this->projectsRepository,
        );

        if(isset($this->projectDTO->teamUsers))
        {
            $this->teamUsers = TeamUsersValidations::teamUsersExists(
                $this->projectDTO->teamUsers,
                $this->teamUsersRepository,
            );
        }
    }

    private function handlePopulateDefaultData(): void
    {
        $this->projectDTO->iconId     = $this->iconsRepository->findByName('AnchorIcon')->id;
        $this->projectDTO->uniqueName = Helpers::stringUniqueName($this->projectDTO->name);
    }
}
