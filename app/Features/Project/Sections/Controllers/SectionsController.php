<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Controllers;

use App\Features\Project\Sections\Contracts\CreateSectionBusinessInterface;
use App\Features\Project\Sections\Contracts\FindAllSectionsBusinessInterface;
use App\Features\Project\Sections\Contracts\RemoveSectionBusinessInterface;
use App\Features\Project\Sections\Contracts\ShowSectionBusinessInterface;
use App\Features\Project\Sections\Contracts\UpdateSectionBusinessInterface;
use App\Features\Project\Sections\DTO\SectionsDTO;
use App\Features\Project\Sections\DTO\SectionsFiltersDTO;
use App\Features\Project\Sections\Requests\SectionsFiltersRequest;
use App\Features\Project\Sections\Requests\SectionsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class SectionsController
{
    public function __construct(
        private FindAllSectionsBusinessInterface $findAllSectionsBusiness,
        private ShowSectionBusinessInterface     $showSectionBusiness,
        private CreateSectionBusinessInterface   $createSectionBusiness,
        private UpdateSectionBusinessInterface   $updateSectionBusiness,
        private RemoveSectionBusinessInterface   $removeSectionBusiness,
    ) {}

    public function index(
        SectionsFiltersRequest $request,
        SectionsFiltersDTO $filtersDTO
    ): JsonResponse
    {
        $filtersDTO->projectId = $request->projectId;

        $result = $this->findAllSectionsBusiness->handle($filtersDTO);

        return response()->json($result, Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->id;

        $result = $this->showSectionBusiness->handle($id);

        return response()->json($result, Response::HTTP_OK);
    }

    public function insert(
        SectionsDTO $sectionsDTO,
        SectionsRequest $sectionsRequest,
    ): JsonResponse
    {
        $sectionsDTO->projectId = $sectionsRequest->projectId;
        $sectionsDTO->colorId   = $sectionsRequest->colorId;
        $sectionsDTO->iconId    = $sectionsRequest->iconId;
        $sectionsDTO->name      = $sectionsRequest->name;

        $result = $this->createSectionBusiness->handle($sectionsDTO);

        return response()->json($result, Response::HTTP_CREATED);
    }

    public function update(
        SectionsDTO $sectionsDTO,
        SectionsRequest $sectionsRequest,
    ): JsonResponse
    {
        $sectionsDTO->id      = $sectionsRequest->id;
        $sectionsDTO->colorId = $sectionsRequest->colorId;
        $sectionsDTO->iconId  = $sectionsRequest->iconId;
        $sectionsDTO->name    = $sectionsRequest->name;

        $result = $this->updateSectionBusiness->handle($sectionsDTO);

        return response()->json($result, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removeSectionBusiness->handle($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
