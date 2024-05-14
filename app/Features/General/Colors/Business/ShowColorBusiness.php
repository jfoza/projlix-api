<?php
declare(strict_types=1);

namespace App\Features\General\Colors\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Colors\Contracts\ColorsRepositoryInterface;
use App\Features\General\Colors\Contracts\ShowColorBusinessInterface;
use App\Features\General\Colors\Validations\ColorsValidations;
use App\Shared\Enums\RulesEnum;

class ShowColorBusiness extends Business implements ShowColorBusinessInterface
{
    public function __construct(
        private readonly ColorsRepositoryInterface $colorsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::COLORS_VIEW->value);

        return ColorsValidations::colorExists(
            $id,
            $this->colorsRepository,
        );
    }
}
