<?php

namespace App\Features\User\TeamUsers\Controllers;

use App\Features\User\TeamUsers\Contracts\CreateTeamUserBusinessInterface;
use App\Features\User\TeamUsers\Contracts\FindAllTeamUsersBusinessInterface;
use App\Features\User\TeamUsers\Contracts\ShowTeamUserBusinessInterface;
use App\Features\User\TeamUsers\Contracts\UpdateStatusTeamUserBusinessInterface;
use App\Features\User\TeamUsers\Contracts\UpdateTeamUserBusinessInterface;
use App\Features\User\TeamUsers\DTO\TeamUsersFiltersDTO;
use App\Features\User\TeamUsers\Requests\TeamUsersFiltersRequest;
use App\Features\User\TeamUsers\Requests\TeamUsersRequest;
use App\Features\User\Users\DTO\UserDTO;
use App\Features\User\Users\DTO\UsersFiltersDTO;
use App\Features\User\Users\Requests\UsersFiltersRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class TeamUsersController
{
    public function __construct(
        private FindAllTeamUsersBusinessInterface     $findAllTeamUsersBusiness,
        private ShowTeamUserBusinessInterface         $showTeamUserBusiness,
        private CreateTeamUserBusinessInterface       $createTeamUserBusiness,
        private UpdateTeamUserBusinessInterface       $updateTeamUserBusiness,
        private UpdateStatusTeamUserBusinessInterface $updateStatusTeamUserBusiness,
    ) {}

    public function index(
        TeamUsersFiltersRequest $filtersRequest,
        TeamUsersFiltersDTO $filtersDTO
    ): JsonResponse
    {
        $filtersDTO->paginationOrder->setColumnOrder($filtersRequest->columnOrder);
        $filtersDTO->paginationOrder->setColumnName($filtersRequest->columnName);
        $filtersDTO->paginationOrder->setPerPage($filtersRequest->perPage);
        $filtersDTO->paginationOrder->setPage($filtersRequest->page);

        $filtersDTO->name       = $filtersRequest->name;
        $filtersDTO->email      = $filtersRequest->email;
        $filtersDTO->profileId  = $filtersRequest->profileId;
        $filtersDTO->projectsId = isset($filtersRequest->projectId) ? [$filtersRequest->projectId] : [];
        $filtersDTO->active     = isset($filtersRequest->active) ? (bool) $filtersRequest->active : null;

        $result = $this->findAllTeamUsersBusiness->handle($filtersDTO);

        return response()->json($result, Response::HTTP_OK);
    }

    public function showById(Request $request): JsonResponse
    {
        $userId = $request->id;

        $result = $this->showTeamUserBusiness->handle($userId);

        return response()->json($result, Response::HTTP_OK);
    }

    public function insert(
        TeamUsersRequest $teamUsersRequest,
        UserDTO $userDTO
    ): JsonResponse
    {
        $userDTO->name       = $teamUsersRequest->name;
        $userDTO->email      = $teamUsersRequest->email;
        $userDTO->profileId  = $teamUsersRequest->profileId;
        $userDTO->projectsId = $teamUsersRequest->projectsId;

        $adminUserCreated = $this->createTeamUserBusiness->handle($userDTO);

        return response()->json($adminUserCreated, Response::HTTP_CREATED);
    }

    public function update(
        TeamUsersRequest $teamUsersRequest,
        UserDTO $userDTO
    ): JsonResponse
    {
        $userDTO->id         = $teamUsersRequest->id;
        $userDTO->name       = $teamUsersRequest->name;
        $userDTO->email      = $teamUsersRequest->email;
        $userDTO->profileId  = $teamUsersRequest->profileId;
        $userDTO->projectsId = isset($teamUsersRequest->projectsId) ? $teamUsersRequest->projectsId : [];

        $adminUserUpdated = $this->updateTeamUserBusiness->handle($userDTO);

        return response()->json($adminUserUpdated, Response::HTTP_OK);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $id = $request->id;

        $adminUserUpdated = $this->updateStatusTeamUserBusiness->handle($id);

        return response()->json($adminUserUpdated, Response::HTTP_OK);
    }
}
