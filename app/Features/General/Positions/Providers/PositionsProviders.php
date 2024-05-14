<?php

namespace App\Features\General\Positions\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\General\Positions\Business\CreatePositionBusiness;
use App\Features\General\Positions\Business\FindAllPositionsBusiness;
use App\Features\General\Positions\Business\RemovePositionBusiness;
use App\Features\General\Positions\Business\ShowPositionBusiness;
use App\Features\General\Positions\Business\UpdatePositionBusiness;
use App\Features\General\Positions\Contracts\CreatePositionBusinessInterface;
use App\Features\General\Positions\Contracts\FindAllPositionsBusinessInterface;
use App\Features\General\Positions\Contracts\PositionsRepositoryInterface;
use App\Features\General\Positions\Contracts\RemovePositionBusinessInterface;
use App\Features\General\Positions\Contracts\ShowPositionBusinessInterface;
use App\Features\General\Positions\Contracts\UpdatePositionBusinessInterface;
use App\Features\General\Positions\Repositories\PositionsRepository;

class PositionsProviders extends ServiceProvider
{
    public array $bindings = [
        PositionsRepositoryInterface::class => PositionsRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllPositionsBusinessInterface::class,
            FindAllPositionsBusiness::class
        );

        $this->bind(
            ShowPositionBusinessInterface::class,
            ShowPositionBusiness::class
        );

        $this->bind(
            CreatePositionBusinessInterface::class,
            CreatePositionBusiness::class
        );

        $this->bind(
            UpdatePositionBusinessInterface::class,
            UpdatePositionBusiness::class
        );

        $this->bind(
            RemovePositionBusinessInterface::class,
            RemovePositionBusiness::class
        );
    }
}
