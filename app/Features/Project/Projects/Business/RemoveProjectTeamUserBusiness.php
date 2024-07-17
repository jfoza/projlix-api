<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\RemoveProjectTeamUserBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Features\User\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\Validations\TeamUsersValidations;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Symfony\Component\HttpFoundation\Response;

class RemoveProjectTeamUserBusiness extends Business implements RemoveProjectTeamUserBusinessInterface
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
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_TEAM_USER_DELETE->value)    => $this->removeByAdminMaster(),
            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_TEAM_USER_DELETE->value) => $this->removeByProjectManager(),
            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_TEAM_USER_DELETE->value)     => $this->removeByTeamLeader(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function removeByAdminMaster(): void
    {
        $this->handleValidations();
        $this->removeProjectTeamUser();
    }

    /**
     * @throws AppException
     */
    private function removeByProjectManager(): void
    {
        $this->handleValidations();

        $this->canAccessProjects([$this->projectDTO->id]);

        $this->profileHierarchyValidation(
            $this->teamUser->profile_unique_name,
            ProfileUniqueNameEnum::PROFILES_BY_PROJECT_MANAGER,
            MessagesEnum::USER_NOT_ALLOWED->value
        );

        $this->removeProjectTeamUser();
    }

    /**
     * @throws AppException
     */
    private function removeByTeamLeader(): void
    {
        $this->handleValidations();

        $this->canAccessProjects([$this->projectDTO->id]);

        $this->profileHierarchyValidation(
            $this->teamUser->profile_unique_name,
            ProfileUniqueNameEnum::PROFILES_BY_TEAM_LEADER,
            MessagesEnum::USER_NOT_ALLOWED->value
        );

        $this->removeProjectTeamUser();
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

        if(!$project->teamUsers->where('team_user_id', $this->teamUser->id)->first())
        {
            throw new AppException(
                MessagesEnum::PROJECT_TEAM_USER_ALREADY_REMOVED->value,
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @throws AppException
     */
    private function removeProjectTeamUser(): void
    {
        Transaction::beginTransaction();

        try
        {
            $this->projectsRepository->detachTeamUser(
                $this->projectDTO->id,
                $this->projectDTO->teamUserId
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
