<?php

namespace App\Features\General\Positions\Validations;

use App\Exceptions\AppException;
use App\Features\General\Positions\Contracts\PositionsRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class PositionsValidations
{
    /**
     * @throws AppException
     */
    public static function positionExists(
        string $positionId,
        PositionsRepositoryInterface $positionsRepository
    ): ?object
    {
        if(!$position = $positionsRepository->findById($positionId))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $position;
    }

    /**
     * @throws AppException
     */
    public static function positionExistsByName(
        string $name,
        PositionsRepositoryInterface $positionsRepository
    ): ?object
    {
        if($position = $positionsRepository->findByName($name))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NAME_ALREADY_EXISTS,
                Response::HTTP_NOT_FOUND
            );
        }

        return $position;
    }

    /**
     * @throws AppException
     */
    public static function positionExistsByNameInUpdate(
        string $id,
        string $name,
        PositionsRepositoryInterface $positionsRepository
    ): ?object
    {
        $position = $positionsRepository->findByName($name);

        if($position && $position->id != $id)
        {
            throw new AppException(
                MessagesEnum::REGISTER_NAME_ALREADY_EXISTS,
                Response::HTTP_NOT_FOUND
            );
        }

        return $position;
    }
}
