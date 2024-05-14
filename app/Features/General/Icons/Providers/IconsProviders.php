<?php
declare(strict_types=1);

namespace App\Features\General\Icons\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\General\Icons\Business\FindAllIconsBusiness;
use App\Features\General\Icons\Business\ShowIconBusiness;
use App\Features\General\Icons\Contracts\FindAllIconsBusinessInterface;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\General\Icons\Contracts\ShowIconBusinessInterface;
use App\Features\General\Icons\Repositories\IconsRepository;

class IconsProviders extends ServiceProvider
{
    public array $bindings = [
        IconsRepositoryInterface::class => IconsRepository::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->bind(
            FindAllIconsBusinessInterface::class,
            FindAllIconsBusiness::class
        );

        $this->bind(
            ShowIconBusinessInterface::class,
            ShowIconBusiness::class
        );
    }
}
