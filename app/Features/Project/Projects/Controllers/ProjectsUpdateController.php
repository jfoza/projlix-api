<?php

namespace App\Features\Project\Projects\Controllers;

use App\Features\Project\Projects\Contracts\AddProjectTeamUserBusinessInterface;
use App\Features\Project\Projects\Contracts\RemoveProjectTagBusinessInterface;
use App\Features\Project\Projects\Contracts\RemoveProjectTeamUserBusinessInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectIconBusinessInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectInfoBusinessInterface;
use App\Features\Project\Projects\Contracts\AddProjectTagBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Requests\UpdateProjectIconRequest;
use App\Features\Project\Projects\Requests\UpdateProjectInfoRequest;
use App\Features\Project\Projects\Requests\UpdateProjectTagsRequest;
use App\Features\Project\Projects\Requests\UpdateProjectTeamUserRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ProjectsUpdateController
{
    public function __construct(
        private UpdateProjectInfoBusinessInterface     $updateProjectInfoBusiness,
        private UpdateProjectIconBusinessInterface     $updateProjectIconBusiness,
        private AddProjectTagBusinessInterface         $addProjectTagBusiness,
        private AddProjectTeamUserBusinessInterface    $addProjectTeamUserBusiness,
        private RemoveProjectTagBusinessInterface      $removeProjectTagBusiness,
        private RemoveProjectTeamUserBusinessInterface $removeProjectTeamUserBusiness,
    ) {}

    public function updateInfo(
        UpdateProjectInfoRequest $projectRequest,
        ProjectDTO $projectDTO,
    ): JsonResponse
    {
        $projectDTO->id          = $projectRequest->id;
        $projectDTO->name        = $projectRequest->name;
        $projectDTO->description = $projectRequest->description;

        $project = $this->updateProjectInfoBusiness->handle($projectDTO);

        return response()->json($project, Response::HTTP_OK);
    }

    public function addTag(
        UpdateProjectTagsRequest $projectRequest,
        ProjectDTO $projectDTO,
    ): JsonResponse
    {
        $projectDTO->id    = $projectRequest->id;
        $projectDTO->tagId = $projectRequest->tagId;

        $this->addProjectTagBusiness->handle($projectDTO);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function addTeamUser(
        UpdateProjectTeamUserRequest $projectTeamUserRequest,
        ProjectDTO $projectDTO,
    ): JsonResponse
    {
        $projectDTO->id         = $projectTeamUserRequest->id;
        $projectDTO->teamUserId = $projectTeamUserRequest->teamUserId;

        $this->addProjectTeamUserBusiness->handle($projectDTO);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function updateIcon(
        UpdateProjectIconRequest $projectRequest,
        ProjectDTO $projectDTO,
    ): JsonResponse
    {
        $projectDTO->id     = $projectRequest->id;
        $projectDTO->iconId = $projectRequest->iconId;

        $this->updateProjectIconBusiness->handle($projectDTO);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function removeTag(
        UpdateProjectTagsRequest $projectRequest,
        ProjectDTO $projectDTO,
    ): JsonResponse
    {
        $projectDTO->id    = $projectRequest->id;
        $projectDTO->tagId = $projectRequest->tagId;

        $this->removeProjectTagBusiness->handle($projectDTO);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function removeTeamUser(
        UpdateProjectTeamUserRequest $projectTeamUserRequest,
        ProjectDTO $projectDTO,
    ): JsonResponse
    {
        $projectDTO->id         = $projectTeamUserRequest->id;
        $projectDTO->teamUserId = $projectTeamUserRequest->teamUserId;

        $this->removeProjectTeamUserBusiness->handle($projectDTO);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
