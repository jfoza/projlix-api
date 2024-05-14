<?php
declare(strict_types=1);

namespace App\Features\User\Profiles\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\User\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\User\Profiles\Repositories\ProfilesRepository;

class ProfilesProviders extends ServiceProvider
{
    public array $bindings = [
        ProfilesRepositoryInterface::class => ProfilesRepository::class,
    ];
}
