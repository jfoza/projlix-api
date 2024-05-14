<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\Contracts\AuthBusinessInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Requests\AuthRequest;
use App\Shared\Enums\AuthTypesEnum;
use App\Shared\Utils\Auth;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class AuthController
{
    public function __construct(
        private AuthBusinessInterface $authBusiness,
    ) {}

    public function create(
        AuthRequest $authRequest,
        AuthDTO $authDTO,
    ): JsonResponse
    {
        $authDTO->authType  = AuthTypesEnum::EMAIL_PASSWORD->value;
        $authDTO->email     = $authRequest->email;
        $authDTO->password  = $authRequest->password;
        $authDTO->ipAddress = $authRequest->ip();

        $auth = $this->authBusiness->execute($authDTO);

        return response()->json($auth, Response::HTTP_OK);
    }

    public function createWithGoogle(): JsonResponse
    {
        return response()->json([], Response::HTTP_OK);
    }

    public function destroy(): JsonResponse
    {
        auth()->invalidate(true);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
