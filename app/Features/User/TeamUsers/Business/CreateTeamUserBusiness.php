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
use App\Features\User\TeamUsers\Contracts\CreateTeamUserBusinessInterface;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\Responses\SavedTeamUserResponse;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\DTO\UserDTO;
use App\Features\User\Users\Validations\UsersValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;
use Illuminate\Support\Collection;

class CreateTeamUserBusiness extends Business implements CreateTeamUserBusinessInterface
{
    private UserDTO $userDTO;
    private object $profile;
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
            $policy->haveRule(RulesEnum::TEAM_USERS_ADMIN_MASTER_INSERT->value),
            $policy->haveRule(RulesEnum::TEAM_USERS_PROJECT_MANAGER_INSERT->value)
                => $this->insertByAdminMasterProjectManager(),

            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_INSERT->value) => $this->insertByTeamLeader(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function insertByAdminMasterProjectManager(): SavedTeamUserResponse
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

        return $this->insertTeamUser();
    }

    /**
     * @throws AppException
     */
    private function insertByTeamLeader(): SavedTeamUserResponse
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

        return $this->insertTeamUser();
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        UsersValidations::emailAlreadyExists($this->userDTO->email, $this->usersRepository);

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
    private function insertTeamUser(): SavedTeamUserResponse
    {
        Transaction::beginTransaction();

        try
        {
            $this->userDTO->password  = Hash::generateHash(RandomStringHelper::alnumGenerate(6));
            $this->userDTO->shortName = strtoupper(substr($this->userDTO->name, 0, 2));

            $userCreated = $this->usersRepository->create($this->userDTO);

            $teamUserCreated = $this->teamUsersRepository->create($userCreated->id);

            $this->usersRepository->saveProfiles($userCreated->id, [$this->userDTO->profileId]);

            $this->relateProjectsToTeamUsers(
                $teamUserCreated->id,
                $this->userDTO->projectsId
            );

            Transaction::commit();

            return SavedTeamUserResponse::setUp(
                $userCreated->id,
                $userCreated->name,
                $userCreated->email,
                $userCreated->active,
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
