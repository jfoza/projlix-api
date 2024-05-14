<?php

namespace App\Features\Base\Business;

use App\Exceptions\AppException;
use App\Features\Project\Projects\Models\Project;
use App\Features\User\Profiles\Models\Profile;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

abstract class Business extends BaseBusiness
{
    private object $authenticatedUser;

    /**
     * @param object $authenticatedUser
     */
    public function setAuthenticatedUser(object $authenticatedUser): void
    {
        $this->authenticatedUser = $authenticatedUser;
    }

    /**
     * @return object
     */
    public function getAuthenticatedUser(): object
    {
        return $this->authenticatedUser;
    }

    public function getAuthenticatedUserId(): string
    {
        return $this->getAuthenticatedUser()->id;
    }

    public function getProfilesUser(): Collection
    {
        $user = $this->getAuthenticatedUser();

        return collect($user->profile);
    }

    public function getProfilesId(): array
    {
        return $this->getProfilesUser()->pluck(Profile::ID)->toArray();
    }

    public function getTeamUserId(): string
    {
        $user = $this->getAuthenticatedUser();

        return $user->teamUser->id;
    }

    public function getTeamUserProjects(): Collection
    {
        $user = $this->getAuthenticatedUser();

        return collect($user->teamUser->projects);
    }

    public function getTeamUserProjectsId(): array
    {
        return $this->getTeamUserProjects()->pluck(Project::ID)->toArray();
    }

    public function userPayloadIsEqualsAuthUser(string $userId): bool
    {
        return $this->getAuthenticatedUserId() == $userId;
    }

    /**
     * @throws AppException
     */
    public function canAccessProjects(array $projectsId, string $message = null): void
    {
        if($this->getTeamUserProjects()->whereIn(Project::ID, $projectsId)->isEmpty())
        {
            throw new AppException(
                $message ?: MessagesEnum::PROJECT_NOT_ALLOWED,
                Response::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * @throws AppException
     */
    public function canAccessEachProject(array $projectsId, string $message = null): void
    {
        foreach ($projectsId as $projectId)
        {
            if(!$this->getTeamUserProjects()->firstWhere(Project::ID, $projectId))
            {
                throw new AppException(
                    $message ?: MessagesEnum::PROJECT_NOT_ALLOWED,
                    Response::HTTP_FORBIDDEN
                );
            }
        }
    }

    /**
     * @throws AppException
     */
    public function canAccessProfile(string $profileId): void
    {
        if(empty($this->getProfilesUser()->firstWhere(Profile::ID, $profileId)))
        {
            throw new AppException(
                MessagesEnum::PROFILE_NOT_ALLOWED,
                Response::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * @throws AppException
     */
    public function canAccessNote(string $userId): void
    {
        if($userId != $this->getAuthenticatedUserId())
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_ALLOWED,
                Response::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * @throws AppException
     */
    public function profileHierarchyValidation(string $needle, array $haystack): void
    {
        if(!in_array($needle, $haystack))
        {
            throw new AppException(
                MessagesEnum::PROFILE_NOT_ALLOWED,
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
