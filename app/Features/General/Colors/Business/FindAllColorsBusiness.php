<?php
declare(strict_types=1);

namespace App\Features\General\Colors\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Colors\Contracts\ColorsRepositoryInterface;
use App\Features\General\Colors\Contracts\FindAllColorsBusinessInterface;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;

class FindAllColorsBusiness extends Business implements FindAllColorsBusinessInterface
{
    public function __construct(
        private readonly ColorsRepositoryInterface $colorsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(): Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::COLORS_VIEW->value);

        return $this->colorsRepository->findAll();
    }
}
