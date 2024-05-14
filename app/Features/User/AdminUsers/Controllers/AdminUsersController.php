<?php

namespace App\Features\User\AdminUsers\Controllers;

use App\Features\User\AdminUsers\Contracts\CreateAdminUserBusinessInterface;
use App\Features\User\AdminUsers\Contracts\ShowAdminUserBusinessInterface;
use App\Features\User\AdminUsers\Contracts\FindAllAdminUsersBusinessInterface;
use App\Features\User\AdminUsers\Contracts\UpdateAdminUserBusinessInterface;
use App\Features\User\AdminUsers\Contracts\UpdateStatusAdminUserBusinessInterface;
use App\Features\User\AdminUsers\Requests\AdminUsersRequest;
use App\Features\User\Users\DTO\UserDTO;
use App\Features\User\Users\DTO\UsersFiltersDTO;
use App\Features\User\Users\Requests\UsersFiltersRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class AdminUsersController
{
    public function __construct(
        private FindAllAdminUsersBusinessInterface     $findAllAdminUsersBusiness,
        private ShowAdminUserBusinessInterface         $findAdminUserByIdBusiness,
        private CreateAdminUserBusinessInterface       $createAdminUserBusiness,
        private UpdateAdminUserBusinessInterface       $updateAdminUserBusiness,
        private UpdateStatusAdminUserBusinessInterface $updateStatusAdminUserBusiness,
    ) {}

    public function index(
        UsersFiltersRequest $usersFiltersRequest,
        UsersFiltersDTO $usersFiltersDTO
    ): JsonResponse
    {
        $usersFiltersDTO->paginationOrder->setColumnOrder($usersFiltersRequest->columnOrder);
        $usersFiltersDTO->paginationOrder->setColumnName($usersFiltersRequest->columnName);
        $usersFiltersDTO->paginationOrder->setPerPage($usersFiltersRequest->perPage);
        $usersFiltersDTO->paginationOrder->setPage($usersFiltersRequest->page);

        $usersFiltersDTO->name  = $usersFiltersRequest->name;
        $usersFiltersDTO->email = $usersFiltersRequest->email;

        $result = $this->findAllAdminUsersBusiness->handle($usersFiltersDTO);

        return response()->json($result, Response::HTTP_OK);
    }

    public function showById(Request $request): JsonResponse
    {
        $userId = $request->id;

        $result = $this->findAdminUserByIdBusiness->handle($userId);

        return response()->json($result, Response::HTTP_OK);
    }

    public function insert(
        AdminUsersRequest $adminUsersRequest,
        UserDTO $userDTO
    ): JsonResponse
    {
        $userDTO->name     = $adminUsersRequest->name;
        $userDTO->email    = $adminUsersRequest->email;

        $adminUserCreated = $this->createAdminUserBusiness->handle($userDTO);

        return response()->json($adminUserCreated, Response::HTTP_CREATED);
    }

    public function update(
        AdminUsersRequest $adminUsersRequest,
        UserDTO $userDTO
    ): JsonResponse
    {
        $userDTO->id       = $adminUsersRequest->id;
        $userDTO->name     = $adminUsersRequest->name;
        $userDTO->email    = $adminUsersRequest->email;

        $adminUserUpdated = $this->updateAdminUserBusiness->handle($userDTO);

        return response()->json($adminUserUpdated, Response::HTTP_OK);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $id = $request->id;

        $adminUserUpdated = $this->updateStatusAdminUserBusiness->handle($id);

        return response()->json($adminUserUpdated, Response::HTTP_OK);
    }
}
