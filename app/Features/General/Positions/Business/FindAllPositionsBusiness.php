<?php

namespace App\Features\General\Positions\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Positions\Contracts\FindAllPositionsBusinessInterface;
use App\Features\General\Positions\Contracts\PositionsRepositoryInterface;
use App\Features\General\Positions\DTO\PositionsFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllPositionsBusiness extends Business implements FindAllPositionsBusinessInterface
{
    public function __construct(
        private readonly PositionsRepositoryInterface $positionsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(PositionsFiltersDTO $positionsFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::POSITIONS_VIEW->value);

        return $this->positionsRepository->findAll($positionsFiltersDTO);
    }
}
