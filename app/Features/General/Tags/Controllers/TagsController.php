<?php

namespace App\Features\General\Tags\Controllers;

use App\Features\Base\Requests\FormRequest;
use App\Features\General\Tags\Contracts\CreateTagBusinessInterface;
use App\Features\General\Tags\Contracts\FindAllTagsBusinessInterface;
use App\Features\General\Tags\Contracts\RemoveTagBusinessInterface;
use App\Features\General\Tags\Contracts\ShowTagBusinessInterface;
use App\Features\General\Tags\Contracts\UpdateStatusTagBusinessInterface;
use App\Features\General\Tags\Contracts\UpdateTagBusinessInterface;
use App\Features\General\Tags\DTO\TagsDTO;
use App\Features\General\Tags\DTO\TagsFiltersDTO;
use App\Features\General\Tags\Requests\TagsFiltersRequest;
use App\Features\General\Tags\Requests\TagsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class TagsController
{
    public function __construct(
        private FindAllTagsBusinessInterface     $findAllTagsBusiness,
        private ShowTagBusinessInterface         $showTagBusiness,
        private CreateTagBusinessInterface       $createTagBusiness,
        private UpdateTagBusinessInterface       $updateTagBusiness,
        private UpdateStatusTagBusinessInterface $updateStatusTagBusiness,
        private RemoveTagBusinessInterface       $removeTagBusiness,
    ) {}

    public function index(
        TagsFiltersRequest $filtersRequest,
        TagsFiltersDTO $filtersDTO
    ): JsonResponse
    {
        $filtersDTO->name = $filtersRequest->name;

        $filtersDTO->paginationOrder->setPage($filtersRequest[FormRequest::PAGE]);
        $filtersDTO->paginationOrder->setPerPage($filtersRequest[FormRequest::PER_PAGE]);
        $filtersDTO->paginationOrder->setColumnOrder($filtersRequest[FormRequest::COLUMN_ORDER]);
        $filtersDTO->paginationOrder->setColumnName($filtersRequest[FormRequest::COLUMN_NAME]);

        $result = $this->findAllTagsBusiness->handle($filtersDTO);

        return response()->json($result, Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->id;

        $result = $this->showTagBusiness->handle($id);

        return response()->json($result, Response::HTTP_OK);
    }

    public function insert(
        TagsDTO $tagsDTO,
        TagsRequest $tagsRequest
    ): JsonResponse
    {
        $tagsDTO->name = $tagsRequest->name;

        $result = $this->createTagBusiness->handle($tagsDTO);

        return response()->json($result, Response::HTTP_CREATED);
    }

    public function update(
        TagsDTO $tagsDTO,
        TagsRequest $tagsRequest
    ): JsonResponse
    {
        $tagsDTO->id   = $tagsRequest->id;
        $tagsDTO->name = $tagsRequest->name;

        $result = $this->updateTagBusiness->handle($tagsDTO);

        return response()->json($result, Response::HTTP_CREATED);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $id = $request->id;

        $result = $this->updateStatusTagBusiness->handle($id);

        return response()->json($result, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removeTagBusiness->handle($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
