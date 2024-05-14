<?php

namespace App\Features\General\Positions\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Positions\Contracts\CreatePositionBusinessInterface;
use App\Features\General\Positions\Contracts\PositionsRepositoryInterface;
use App\Features\General\Positions\DTO\PositionsDTO;
use App\Features\General\Positions\Validations\PositionsValidations;
use App\Shared\Enums\RulesEnum;

class CreatePositionBusiness extends Business implements CreatePositionBusinessInterface
{
    public function __construct(
        private readonly PositionsRepositoryInterface $positionsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(PositionsDTO $positionsDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::POSITIONS_INSERT->value);

        PositionsValidations::positionExistsByName(
            $positionsDTO->name,
            $this->positionsRepository
        );

        return $this->positionsRepository->create($positionsDTO);
    }
}
