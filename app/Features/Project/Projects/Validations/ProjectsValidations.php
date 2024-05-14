<?php

namespace App\Features\Project\Projects\Validations;

use App\Exceptions\AppException;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Models\Project;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ProjectsValidations
{
    /**
     * @throws AppException
     */
    public static function projectExists(
        string $projectId,
        ProjectsRepositoryInterface $projectsRepository,
    ): object
    {
        if(!$project = $projectsRepository->findById($projectId))
        {
            throw new AppException(
                MessagesEnum::PROJECT_NOT_FOUND->value,
                Response::HTTP_NOT_FOUND
            );
        }

        return $project;
    }

    /**
     * @throws AppException
     */
    public static function projectsExists(
        array $projectsId,
        ProjectsRepositoryInterface $teamUsersRepository,
    ): Collection
    {
        $projects = $teamUsersRepository->findByIds($projectsId);

        $ids = $projects->pluck(Project::ID)->toArray();

        foreach ($projectsId as $projectId)
        {
            if(!in_array($projectId, $ids))
            {
                throw new AppException(
                    MessagesEnum::PROJECT_NOT_FOUND->value,
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        return $projects;
    }

    /**
     * @throws AppException
     */
    public static function projectExistsByName(
        string $name,
        ProjectsRepositoryInterface $projectsRepository
    ): ?object
    {
        if($project = $projectsRepository->findByName($name))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NAME_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }

        return $project;
    }

    /**
     * @throws AppException
     */
    public static function projectExistsByNameInUpdate(
        string $id,
        string $name,
        ProjectsRepositoryInterface $projectsRepository
    ): ?object
    {
        $project = $projectsRepository->findByName($name);

        if($project && $project->id != $id)
        {
            throw new AppException(
                MessagesEnum::REGISTER_NAME_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }

        return $project;
    }
}
