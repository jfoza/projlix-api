<?php
declare(strict_types=1);

namespace App\Features\User\AdminUsers\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\User\AdminUsers\Business\CreateAdminUserBusiness;
use App\Features\User\AdminUsers\Business\ShowAdminUserBusiness;
use App\Features\User\AdminUsers\Business\FindAllAdminUsersBusiness;
use App\Features\User\AdminUsers\Business\UpdateAdminUserBusiness;
use App\Features\User\AdminUsers\Business\UpdateStatusAdminUserBusiness;
use App\Features\User\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\User\AdminUsers\Contracts\CreateAdminUserBusinessInterface;
use App\Features\User\AdminUsers\Contracts\ShowAdminUserBusinessInterface;
use App\Features\User\AdminUsers\Contracts\FindAllAdminUsersBusinessInterface;
use App\Features\User\AdminUsers\Contracts\UpdateAdminUserBusinessInterface;
use App\Features\User\AdminUsers\Contracts\UpdateStatusAdminUserBusinessInterface;
use App\Features\User\AdminUsers\Repositories\AdminUsersRepository;

class AdminUsersProviders extends ServiceProvider
{
   public array $bindings = [
       AdminUsersRepositoryInterface::class => AdminUsersRepository::class,
   ];

   public function register(): void
   {
       parent::register();

       $this->bind(
           FindAllAdminUsersBusinessInterface::class,
           FindAllAdminUsersBusiness::class,
       );

       $this->bind(
           ShowAdminUserBusinessInterface::class,
           ShowAdminUserBusiness::class,
       );

       $this->bind(
           CreateAdminUserBusinessInterface::class,
           CreateAdminUserBusiness::class,
       );

       $this->bind(
           UpdateAdminUserBusinessInterface::class,
           UpdateAdminUserBusiness::class,
       );

       $this->bind(
           UpdateStatusAdminUserBusinessInterface::class,
           UpdateStatusAdminUserBusiness::class,
       );
   }
}
