<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Models\Project;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Features\User\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\User\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\Contracts\UpdateTeamUserBusinessInterface;
use App\Features\User\TeamUsers\Responses\SavedTeamUserResponse;
use App\Features\User\TeamUsers\Validations\TeamUsersValidations;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\DTO\UserDTO;
use App\Features\User\Users\Validations\UsersValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Illuminate\Support\Collection;

class UpdateTeamUserBusiness extends Business implements UpdateTeamUserBusinessInterface
{
    private UserDTO $userDTO;
    private object $profile;
    private object $teamUser;
    private Collection $projects;

    public function __construct(
        private readonly UsersRepositoryInterface     $usersRepository,
        private readonly TeamUsersRepositoryInterface $teamUsersRepository,
        private readonly ProfilesRepositoryInterface  $profilesRepository,
        private readonly ProjectsRepositoryInterface  $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(UserDTO $userDTO): SavedTeamUserResponse
    {
        $this->userDTO = $userDTO;

        $this->projects = Collection::empty();

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::TEAM_USERS_ADMIN_MASTER_UPDATE->value),
            $policy->haveRule(RulesEnum::TEAM_USERS_PROJECT_MANAGER_UPDATE->value)
                => $this->updateByAdminMasterProjectManager(),

            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_UPDATE->value) => $this->updateByTeamLeader(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMasterProjectManager(): SavedTeamUserResponse
    {
        $this->handleValidations();

        $this->profileHierarchyValidation(
            $this->profile->unique_name,
            [
                ProfileUniqueNameEnum::PROJECT_MANAGER,
                ProfileUniqueNameEnum::TEAM_LEADER,
                ProfileUniqueNameEnum::PROJECT_MEMBER,
            ]
        );

        return $this->updateTeamUser();
    }

    /**
     * @throws AppException
     */
    private function updateByTeamLeader(): SavedTeamUserResponse
    {
        $this->handleValidations();

        $this->profileHierarchyValidation(
            $this->profile->unique_name,
            [
                ProfileUniqueNameEnum::TEAM_LEADER,
                ProfileUniqueNameEnum::PROJECT_MEMBER,
            ]
        );

        $this->canAccessEachProject($this->userDTO->projectsId);

        return $this->updateTeamUser();
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        $this->teamUser = TeamUsersValidations::teamUserExistsByUserId($this->userDTO->id, $this->teamUsersRepository);

        UsersValidations::emailAlreadyExistsInUpdate(
            $this->userDTO->id,
            $this->userDTO->email,
            $this->usersRepository
        );

        $this->profile = UsersValidations::profileExists($this->userDTO->profileId, $this->profilesRepository);

        if(isset($this->userDTO->projectsId))
        {
            $this->projects = ProjectsValidations::projectsExists(
                $this->userDTO->projectsId,
                $this->projectsRepository
            );
        }
    }

    /**
     * @throws AppException
     */
    private function updateTeamUser(): SavedTeamUserResponse
    {
        Transaction::beginTransaction();

        try
        {
            $this->userDTO->shortName = strtoupper(substr($this->userDTO->name, 0, 2));

            $userUpdated = $this->usersRepository->update($this->userDTO);

            $this->usersRepository->saveProfiles($userUpdated->id, [$this->userDTO->profileId]);

            $this->relateProjectsToTeamUsers(
                $this->teamUser->team_user_id,
                $this->userDTO->projectsId
            );

            Transaction::commit();

            return SavedTeamUserResponse::setUp(
                $userUpdated->id,
                $userUpdated->name,
                $userUpdated->email,
                $this->teamUser->user->active,
                $this->profile->description,
                $this->projects
            );
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }

    private function relateProjectsToTeamUsers(string $teamUserId, array $projects): void
    {
        if($this->profile->unique_name == ProfileUniqueNameEnum::PROJECT_MANAGER)
        {
            $projects = $this
                ->projectsRepository
                ->findAllWithoutFilters()
                ->pluck(Project::ID)
                ->toArray();
        }

        $this->teamUsersRepository->saveProjects(
            $teamUserId,
            $projects
        );
    }
}
