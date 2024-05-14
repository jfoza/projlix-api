<?php
declare(strict_types=1);

namespace App\Features\User\Users\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\Contracts\UserUpdateStatusServiceInterface;
use App\Features\User\Users\Repositories\UsersRepository;
use App\Features\User\Users\Services\UserUpdateStatusService;

class UsersProviders extends ServiceProvider
{
    public array $bindings = [
        UsersRepositoryInterface::class => UsersRepository::class,

        UserUpdateStatusServiceInterface::class  => UserUpdateStatusService::class,
    ];
}
