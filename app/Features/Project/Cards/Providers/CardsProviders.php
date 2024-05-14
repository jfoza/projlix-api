<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\Project\Cards\Business\CreateCardBusiness;
use App\Features\Project\Cards\Business\FindAllCardsBusiness;
use App\Features\Project\Cards\Business\RemoveCardBusiness;
use App\Features\Project\Cards\Business\ShowCardBusiness;
use App\Features\Project\Cards\Business\UpdateCardBusiness;
use App\Features\Project\Cards\Contracts\CardsRepositoryInterface;
use App\Features\Project\Cards\Contracts\CreateCardBusinessInterface;
use App\Features\Project\Cards\Contracts\FindAllCardsBusinessInterface;
use App\Features\Project\Cards\Contracts\RemoveCardBusinessInterface;
use App\Features\Project\Cards\Contracts\ShowCardBusinessInterface;
use App\Features\Project\Cards\Contracts\UpdateCardBusinessInterface;
use App\Features\Project\Cards\Repositories\CardsRepository;

class CardsProviders extends ServiceProvider
{
    public array $bindings = [
        CardsRepositoryInterface::class  => CardsRepository::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->bind(
            FindAllCardsBusinessInterface::class,
            FindAllCardsBusiness::class
        );

        $this->bind(
            ShowCardBusinessInterface::class,
            ShowCardBusiness::class
        );

        $this->bind(
            CreateCardBusinessInterface::class,
            CreateCardBusiness::class
        );

        $this->bind(
            UpdateCardBusinessInterface::class,
            UpdateCardBusiness::class
        );

        $this->bind(
            RemoveCardBusinessInterface::class,
            RemoveCardBusiness::class
        );
    }
}
