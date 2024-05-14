<?php
declare(strict_types=1);

namespace App\Features\General\Icons\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Icons\Contracts\FindAllIconsBusinessInterface;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;

class FindAllIconsBusiness extends Business implements FindAllIconsBusinessInterface
{
    public function __construct(
        private readonly IconsRepositoryInterface $iconsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(): Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::ICONS_VIEW->value);

        return $this->iconsRepository->findAll();
    }
}
