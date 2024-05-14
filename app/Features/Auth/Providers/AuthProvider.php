<?php
declare(strict_types=1);

namespace App\Features\Auth\Providers;

use App\Features\Auth\Business\AuthBusiness;
use App\Features\Auth\Contracts\AuthBusinessInterface;
use App\Features\Auth\Contracts\AuthGenerateServiceInterface;
use App\Features\Auth\Contracts\AuthRepositoryInterface;
use App\Features\Auth\Contracts\CreateAuthDataServiceInterface;
use App\Features\Auth\Contracts\ShowAuthUserServiceInterface;
use App\Features\Auth\Repositories\AuthRepository;
use App\Features\Auth\Services\AuthGenerateService;
use App\Features\Auth\Services\CreateAuthDataService;
use App\Features\Auth\Services\ShowAuthUserService;
use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{
    public array $bindings = [
        AuthBusinessInterface::class => AuthBusiness::class,
        AuthGenerateServiceInterface::class   => AuthGenerateService::class,
        ShowAuthUserServiceInterface::class   => ShowAuthUserService::class,
        CreateAuthDataServiceInterface::class => CreateAuthDataService::class,

        AuthRepositoryInterface::class => AuthRepository::class,
    ];
}
