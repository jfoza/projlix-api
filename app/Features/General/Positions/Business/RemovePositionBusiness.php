<?php

namespace App\Features\General\Positions\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Positions\Contracts\PositionsRepositoryInterface;
use App\Features\General\Positions\Contracts\RemovePositionBusinessInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Symfony\Component\HttpFoundation\Response;

class RemovePositionBusiness extends Business implements RemovePositionBusinessInterface
{
    public function __construct(
        private readonly PositionsRepositoryInterface $positionsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): void
    {
        $this->getPolicy()->havePermission(RulesEnum::POSITIONS_DELETE->value);

        if(!$this->positionsRepository->findById($id))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        $this->positionsRepository->remove($id);
    }
}
