<?php

namespace App\Features\Project\Cards\Controllers;

use App\Features\Project\Cards\Contracts\CreateCardBusinessInterface;
use App\Features\Project\Cards\Contracts\FindAllCardsBusinessInterface;
use App\Features\Project\Cards\Contracts\RemoveCardBusinessInterface;
use App\Features\Project\Cards\Contracts\ShowCardBusinessInterface;
use App\Features\Project\Cards\Contracts\UpdateCardBusinessInterface;
use App\Features\Project\Cards\DTO\CardDTO;
use App\Features\Project\Cards\DTO\CardFiltersDTO;
use App\Features\Project\Cards\Requests\CardsFiltersRequest;
use App\Features\Project\Cards\Requests\CardsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class CardsController
{
    public function __construct(
        private FindAllCardsBusinessInterface $findAllCardsBusiness,
        private ShowCardBusinessInterface     $showCardBusiness,
        private CreateCardBusinessInterface   $createCardBusiness,
        private UpdateCardBusinessInterface   $updateCardBusiness,
        private RemoveCardBusinessInterface   $removeCardBusiness,
    ) {}

    public function index(
        CardsFiltersRequest $request,
        CardFiltersDTO $filtersDTO
    ): JsonResponse
    {
        $filtersDTO->paginationOrder->setColumnOrder($request->columnOrder);
        $filtersDTO->paginationOrder->setColumnName($request->columnName);
        $filtersDTO->paginationOrder->setPerPage($request->perPage);
        $filtersDTO->paginationOrder->setPage($request->page);

        $filtersDTO->projectsId    = isset($request->projectsId) ? $request->projectsId : [];
        $filtersDTO->code          = $request->code;
        $filtersDTO->sectionId     = $request->sectionId;
        $filtersDTO->initialDate   = $request->initialDate;
        $filtersDTO->finalDate     = $request->finalDate;
        $filtersDTO->responsibleId = $request->responsibleId;
        $filtersDTO->tagsId        = $request->tagsId;

        $result = $this->findAllCardsBusiness->handle($filtersDTO);

        return response()->json($result, Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->id;

        $result = $this->showCardBusiness->handle($id);

        return response()->json($result, Response::HTTP_OK);
    }

    public function insert(
        CardsRequest $request,
        CardDTO $cardDTO
    ): JsonResponse
    {
        $cardDTO->sectionId   = $request->sectionId;
        $cardDTO->tagId       = $request->tagId;
        $cardDTO->responsible = $request->responsible;
        $cardDTO->description = $request->description;
        $cardDTO->limitDate   = $request->limitDate;

        $result = $this->createCardBusiness->handle($cardDTO);

        return response()->json($result, Response::HTTP_CREATED);
    }

    public function update(): JsonResponse
    {

    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removeCardBusiness->handle($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
