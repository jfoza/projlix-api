<?php

namespace App\Shared\Rules;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class Uuid4Rule implements ValidationRule
{
    /**
     * @throws AppException
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!is_string($value))
        {
            throw new AppException(
                MessagesEnum::INVALID_UUID->value,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if(!Uuid::isValid($value))
        {
            throw new AppException(
                MessagesEnum::INVALID_UUID->value,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
