<?php

namespace App\Features\General\Colors\Validations;

use App\Exceptions\AppException;
use App\Features\General\Colors\Contracts\ColorsRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class ColorsValidations
{
    /**
     * @throws AppException
     */
    public static function colorExists(
        string $id,
        ColorsRepositoryInterface $colorsRepository,
    ): object
    {
        if (!$result = $colorsRepository->findById($id)) {
            throw new AppException(
                MessagesEnum::COLOR_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $result;
    }
}
