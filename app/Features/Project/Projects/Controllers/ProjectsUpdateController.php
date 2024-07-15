<?php

namespace App\Features\Project\Projects\Controllers;

use App\Features\Project\Projects\Contracts\RemoveProjectTagBusinessInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectInfoBusinessInterface;
use App\Features\Project\Projects\Contracts\AddProjectTagBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Requests\ProjectRequest;
use App\Features\Project\Projects\Requests\UpdateProjectInfoRequest;
use App\Features\Project\Projects\Requests\UpdateProjectTagsRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ProjectsUpdateController
{
    public function __construct(
        private UpdateProjectInfoBusinessInterface $updateProjectInfoBusiness,
        private AddProjectTagBusinessInterface     $addProjectTagBusiness,
        private RemoveProjectTagBusinessInterface  $removeProjectTagBusiness,
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

    public function updateIcon(
        ProjectRequest $projectRequest,
        ProjectDTO $projectDTO,
    ): JsonResponse
    {

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
}
