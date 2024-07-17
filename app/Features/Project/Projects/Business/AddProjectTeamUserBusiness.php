<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\AddProjectTeamUserBusinessInterface;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Features\User\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\Validations\TeamUsersValidations;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Symfony\Component\HttpFoundation\Response;

class AddProjectTeamUserBusiness extends Business implements AddProjectTeamUserBusinessInterface
{
    private ProjectDTO $projectDTO;
    private ?object $teamUser;

    public function __construct(
        private readonly ProjectsRepositoryInterface $projectsRepository,
        private readonly TeamUsersRepositoryInterface $teamUsersRepository,
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
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_TEAM_USER_INSERT->value)    => $this->addByAdminMaster(),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_TEAM_USER_INSERT->value) => $this->addByProjectManager(),
            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_TEAM_USER_INSERT->value)     => $this->addByTeamLeader(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function addByAdminMaster(): void
    {
        $this->handleValidations();
        $this->addProjectTeamUser();
    }

    /**
     * @throws AppException
     */
    private function addByProjectManager(): void
    {
        $this->handleValidations();

        $this->canAccessProjects([$this->projectDTO->id]);

        $this->profileHierarchyValidation(
            $this->teamUser->profile_unique_name,
            ProfileUniqueNameEnum::PROFILES_BY_PROJECT_MANAGER,
            MessagesEnum::USER_NOT_ALLOWED->value
        );

        $this->addProjectTeamUser();
    }

    /**
     * @throws AppException
     */
    private function addByTeamLeader(): void
    {
        $this->handleValidations();

        $this->canAccessProjects([$this->projectDTO->id]);

        $this->profileHierarchyValidation(
            $this->teamUser->profile_unique_name,
            ProfileUniqueNameEnum::PROFILES_BY_TEAM_LEADER,
            MessagesEnum::USER_NOT_ALLOWED->value
        );

        $this->addProjectTeamUser();
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        $project = ProjectsValidations::projectExists(
            $this->projectDTO->id,
            $this->projectsRepository
        );

        $this->teamUser = TeamUsersValidations::teamUserExists(
            $this->projectDTO->teamUserId,
            $this->teamUsersRepository
        );

        if($project->teamUsers->where('team_user_id', $this->teamUser->id)->first())
        {
            throw new AppException(
                MessagesEnum::PROJECT_TEAM_USER_ALREADY_EXISTS->value,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    private function addProjectTeamUser(): void
    {
        Transaction::beginTransaction();

        try
        {
            $this->projectsRepository->saveTeamUsers(
                $this->projectDTO->id,
                [$this->projectDTO->teamUserId],
            );

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($e);
        }
    }
}
