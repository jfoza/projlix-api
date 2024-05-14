<?php

namespace App\Http\Middleware;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Libraries\Uuid;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateUuid4
{
    /**
     * @throws AppException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Uuid::isValid($request->id))
        {
            throw new AppException(
                MessagesEnum::INVALID_UUID,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $next($request);
    }
}
