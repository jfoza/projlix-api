<?php

namespace App\Features\General\Icons\Validations;

use App\Exceptions\AppException;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class IconsValidations
{
    /**
     * @throws AppException
     */
    public static function iconExists(
        string $id,
        IconsRepositoryInterface $iconsRepository,
    ): object
    {
        if (!$result = $iconsRepository->findById($id)) {
            throw new AppException(
                MessagesEnum::ICON_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $result;
    }
}
