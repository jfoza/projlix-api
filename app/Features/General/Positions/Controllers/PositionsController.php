<?php

namespace App\Features\General\Positions\Controllers;

use App\Features\General\Positions\Contracts\CreatePositionBusinessInterface;
use App\Features\General\Positions\Contracts\FindAllPositionsBusinessInterface;
use App\Features\General\Positions\Contracts\RemovePositionBusinessInterface;
use App\Features\General\Positions\Contracts\ShowPositionBusinessInterface;
use App\Features\General\Positions\Contracts\UpdatePositionBusinessInterface;
use App\Features\General\Positions\DTO\PositionsDTO;
use App\Features\General\Positions\DTO\PositionsFiltersDTO;
use App\Features\General\Positions\Requests\PositionsFiltersRequest;
use App\Features\General\Positions\Requests\PositionsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class PositionsController
{
    public function __construct(
        private FindAllPositionsBusinessInterface $findAllPositionsBusiness,
        private ShowPositionBusinessInterface     $showPositionBusiness,
        private CreatePositionBusinessInterface   $createPositionBusiness,
        private UpdatePositionBusinessInterface   $updatePositionBusiness,
        private RemovePositionBusinessInterface   $removePositionBusiness,
    ) {}

    public function index(
        PositionsFiltersRequest $positionsFiltersRequest,
        PositionsFiltersDTO $positionsFiltersDTO
    ): JsonResponse
    {
        $positionsFiltersDTO->name = $positionsFiltersRequest->name;

        $positionsFiltersDTO->paginationOrder->setPage($positionsFiltersRequest->page);
        $positionsFiltersDTO->paginationOrder->setPerPage($positionsFiltersRequest->perPage);
        $positionsFiltersDTO->paginationOrder->setColumnOrder($positionsFiltersRequest->columnOrder);
        $positionsFiltersDTO->paginationOrder->setColumnName($positionsFiltersRequest->columnName);

        $positions = $this->findAllPositionsBusiness->execute($positionsFiltersDTO);

        return response()->json($positions, Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->id;

        $position = $this->showPositionBusiness->execute($id);

        return response()->json($position, Response::HTTP_OK);
    }

    public function insert(
        PositionsRequest $positionsRequest,
        PositionsDTO $positionsDTO,
    ): JsonResponse
    {
        $positionsDTO->name        = $positionsRequest->name;
        $positionsDTO->description = $positionsRequest->description;
        $positionsDTO->active      = $positionsRequest->active;

        $position = $this->createPositionBusiness->execute($positionsDTO);

        return response()->json($position, Response::HTTP_OK);
    }

    public function update(
        PositionsRequest $positionsRequest,
        PositionsDTO $positionsDTO,
    ): JsonResponse
    {
        $positionsDTO->id          = $positionsRequest->id;
        $positionsDTO->name        = $positionsRequest->name;
        $positionsDTO->description = $positionsRequest->description;
        $positionsDTO->active      = $positionsRequest->active;

        $position = $this->updatePositionBusiness->execute($positionsDTO);

        return response()->json($position, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removePositionBusiness->execute($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
