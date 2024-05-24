<?php
declare(strict_types=1);

namespace App\Features\User\Profiles\Controllers;

use App\Features\User\Profiles\Contracts\FindAllProfilesBusinessInterface;
use App\Features\User\Profiles\DTO\ProfilesFiltersDTO;
use App\Features\User\Profiles\Requests\ProfilesFiltersRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ProfilesController
{
    public function __construct(
        private FindAllProfilesBusinessInterface $findAllProfilesBusiness
    ) {}

    public function index(
        ProfilesFiltersRequest $profilesFiltersRequest,
        ProfilesFiltersDTO $profilesFiltersDTO,
    ): JsonResponse
    {
        $profilesFiltersDTO->profileType = $profilesFiltersRequest->profileType;

        $profiles = $this->findAllProfilesBusiness->handle($profilesFiltersDTO);

        return response()->json($profiles, Response::HTTP_OK);
    }
}
