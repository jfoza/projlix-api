<?php

namespace App\Features\General\Positions\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Positions\Contracts\PositionsRepositoryInterface;
use App\Features\General\Positions\Contracts\ShowPositionBusinessInterface;
use App\Features\General\Positions\Validations\PositionsValidations;
use App\Shared\Enums\RulesEnum;

class ShowPositionBusiness extends Business implements ShowPositionBusinessInterface
{
    public function __construct(
        private readonly PositionsRepositoryInterface $positionsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::POSITIONS_VIEW->value);

        return PositionsValidations::positionExists(
            $id,
            $this->positionsRepository
        );
    }
}
