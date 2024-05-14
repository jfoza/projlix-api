<?php

namespace App\Features\General\Icons\Controllers;

use App\Features\General\Icons\Contracts\FindAllIconsBusinessInterface;
use App\Features\General\Icons\Contracts\ShowIconBusinessInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class IconsController
{
    public function __construct(
        private FindAllIconsBusinessInterface $findAllIconsBusiness,
        private ShowIconBusinessInterface     $showIconBusiness,
    ) {}

    public function index(): JsonResponse
    {
        $result = $this->findAllIconsBusiness->handle();

        return response()->json($result, Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->id;

        $result = $this->showIconBusiness->handle($id);

        return response()->json($result, Response::HTTP_OK);
    }
}
