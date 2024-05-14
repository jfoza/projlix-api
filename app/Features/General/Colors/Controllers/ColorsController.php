<?php

namespace App\Features\General\Colors\Controllers;

use App\Features\General\Colors\Contracts\FindAllColorsBusinessInterface;
use App\Features\General\Colors\Contracts\ShowColorBusinessInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class ColorsController
{
    public function __construct(
        private FindAllColorsBusinessInterface $findAllColorsBusiness,
        private ShowColorBusinessInterface     $showColorBusiness,
    ) {}

    public function index(): JsonResponse
    {
        $colors = $this->findAllColorsBusiness->handle();

        return response()->json($colors, Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->id;

        $color = $this->showColorBusiness->handle($id);

        return response()->json($color, Response::HTTP_OK);
    }
}
