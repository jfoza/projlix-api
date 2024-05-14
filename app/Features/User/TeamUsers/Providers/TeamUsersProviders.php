<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\User\TeamUsers\Business\CreateTeamUserBusiness;
use App\Features\User\TeamUsers\Business\FindAllTeamUsersBusiness;
use App\Features\User\TeamUsers\Business\ShowTeamUserBusiness;
use App\Features\User\TeamUsers\Business\UpdateStatusTeamUserBusiness;
use App\Features\User\TeamUsers\Business\UpdateTeamUserBusiness;
use App\Features\User\TeamUsers\Contracts\CreateTeamUserBusinessInterface;
use App\Features\User\TeamUsers\Contracts\FindAllTeamUsersBusinessInterface;
use App\Features\User\TeamUsers\Contracts\ShowTeamUserBusinessInterface;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\Contracts\UpdateStatusTeamUserBusinessInterface;
use App\Features\User\TeamUsers\Contracts\UpdateTeamUserBusinessInterface;
use App\Features\User\TeamUsers\Repositories\TeamUsersRepository;

class TeamUsersProviders extends ServiceProvider
{
    public array $bindings = [
        TeamUsersRepositoryInterface::class  => TeamUsersRepository::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->bind(
            FindAllTeamUsersBusinessInterface::class,
            FindAllTeamUsersBusiness::class,
        );

        $this->bind(
            ShowTeamUserBusinessInterface::class,
            ShowTeamUserBusiness::class,
        );

        $this->bind(
            CreateTeamUserBusinessInterface::class,
            CreateTeamUserBusiness::class,
        );

        $this->bind(
            UpdateTeamUserBusinessInterface::class,
            UpdateTeamUserBusiness::class,
        );

        $this->bind(
            UpdateStatusTeamUserBusinessInterface::class,
            UpdateStatusTeamUserBusiness::class,
        );
    }
}
