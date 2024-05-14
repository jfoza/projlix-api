<?php
declare(strict_types=1);

namespace App\Features\General\Colors\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\General\Colors\Business\FindAllColorsBusiness;
use App\Features\General\Colors\Business\ShowColorBusiness;
use App\Features\General\Colors\Contracts\ColorsRepositoryInterface;
use App\Features\General\Colors\Contracts\FindAllColorsBusinessInterface;
use App\Features\General\Colors\Contracts\ShowColorBusinessInterface;
use App\Features\General\Colors\Repositories\ColorsRepository;

class ColorsProviders extends ServiceProvider
{
    public array $bindings = [
        ColorsRepositoryInterface::class => ColorsRepository::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->bind(
            FindAllColorsBusinessInterface::class,
            FindAllColorsBusiness::class
        );

        $this->bind(
            ShowColorBusinessInterface::class,
            ShowColorBusiness::class
        );
    }
}
