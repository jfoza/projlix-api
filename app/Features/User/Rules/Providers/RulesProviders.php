<?php
declare(strict_types=1);

namespace App\Features\User\Rules\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\User\Rules\Contracts\RulesRepositoryInterface;
use App\Features\User\Rules\Repositories\RulesRepository;

class RulesProviders extends ServiceProvider
{
    public array $bindings = [
        RulesRepositoryInterface::class => RulesRepository::class,
    ];
}
