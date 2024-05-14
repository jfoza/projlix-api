<?php

namespace App\Features\Project\Projects\Controllers;

use App\Features\Project\Projects\Contracts\CreateProjectBusinessInterface;
use App\Features\Project\Projects\Contracts\FindAllProjectsBusinessInterface;
use App\Features\Project\Projects\Contracts\RemoveProjectBusinessInterface;
use App\Features\Project\Projects\Contracts\ShowProjectBusinessInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\DTO\ProjectsFiltersDTO;
use App\Features\Project\Projects\Requests\ProjectRequest;
use App\Features\Project\Projects\Requests\ProjectsFiltersRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class ProjectsController
{
    public function __construct(
        private FindAllProjectsBusinessInterface $findAllProjectsBusiness,
        private ShowProjectBusinessInterface     $showProjectBusiness,
        private CreateProjectBusinessInterface   $createProjectBusiness,
        private UpdateProjectBusinessInterface   $updateProjectBusiness,
        private RemoveProjectBusinessInterface   $removeProjectBusiness,
    ) {}

    public function index(
        ProjectsFiltersRequest $projectsFiltersRequest,
        ProjectsFIltersDTO $projectsFiltersDTO
    ): JsonResponse
    {
        $projectsFiltersDTO->name = $projectsFiltersRequest->name;

        $projectsFiltersDTO->paginationOrder->setPage($projectsFiltersRequest->page);
        $projectsFiltersDTO->paginationOrder->setPerPage($projectsFiltersRequest->perPage);
        $projectsFiltersDTO->paginationOrder->setColumnOrder($projectsFiltersRequest->columnOrder);
        $projectsFiltersDTO->paginationOrder->setColumnName($projectsFiltersRequest->columnName);

        $projects = $this->findAllProjectsBusiness->handle($projectsFiltersDTO);

        return response()->json($projects, Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->id;

        $project = $this->showProjectBusiness->handle($id);

        return response()->json($project, Response::HTTP_OK);
    }

    public function insert(
        ProjectRequest $projectRequest,
        ProjectDTO $projectDTO,
    ): JsonResponse
    {
        $projectDTO->name        = $projectRequest->name;
        $projectDTO->description = $projectRequest->description;
        $projectDTO->teamUsers   = $projectRequest->teamUsers;

        $project = $this->createProjectBusiness->handle($projectDTO);

        return response()->json($project, Response::HTTP_OK);
    }

    public function update(
        ProjectRequest $projectRequest,
        ProjectDTO $projectDTO,
    ): JsonResponse
    {
        $projectDTO->id          = $projectRequest->id;
        $projectDTO->name        = $projectRequest->name;
        $projectDTO->description = $projectRequest->description;

        $project = $this->updateProjectBusiness->handle($projectDTO);

        return response()->json($project, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removeProjectBusiness->handle($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
