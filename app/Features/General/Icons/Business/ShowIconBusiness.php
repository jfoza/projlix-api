<?php
declare(strict_types=1);

namespace App\Features\General\Icons\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\General\Icons\Contracts\ShowIconBusinessInterface;
use App\Features\General\Icons\Validations\IconsValidations;
use App\Shared\Enums\RulesEnum;

class ShowIconBusiness extends Business implements ShowIconBusinessInterface
{
    public function __construct(
        private readonly IconsRepositoryInterface $iconsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::ICONS_VIEW->value);

        return IconsValidations::iconExists(
            $id,
            $this->iconsRepository,
        );
    }
}
