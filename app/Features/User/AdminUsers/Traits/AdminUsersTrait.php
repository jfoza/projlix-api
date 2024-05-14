<?php

namespace App\Features\User\AdminUsers\Traits;

use App\Features\User\AdminUsers\Models\AdminUser;
use App\Features\User\Profiles\Models\Profile;
use App\Features\User\Users\DTO\UsersFiltersDTO;
use App\Features\User\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HigherOrderWhenProxy;

trait AdminUsersTrait
{
    public function getBaseQuery(): Builder
    {
        $select = [
            User::tableField(User::ID),
            User::tableField(User::NAME),
            User::tableField(User::EMAIL),
            User::tableField(User::ACTIVE),
            User::tableField(User::CREATED_AT),
        ];

        return User::with(['profile'])
            ->select($select)
            ->join(
                AdminUser::tableName(),
                AdminUser::tableField(AdminUser::USER_ID),
                User::tableField(User::ID)
            );
    }

    public function getBaseQueryFilters(
        UsersFiltersDTO $usersFiltersDTO
    ): Builder|HigherOrderWhenProxy
    {
        return $this
            ->getBaseQuery()
            ->when(
                isset($usersFiltersDTO->name),
                fn($aux) => $aux->where(
                    User::tableField(User::NAME),
                    'ILIKE',
                    '%'.$usersFiltersDTO->name.'%'
                )
            )
            ->when(
                isset($usersFiltersDTO->email),
                fn($aux) => $aux->where(
                    User::tableField(User::EMAIL),
                    $usersFiltersDTO->email
                )
            )
            ->when(
                isset($usersFiltersDTO->active),
                fn($aux) => $aux->where(
                    User::tableField(User::ACTIVE),
                    $usersFiltersDTO->active
                )
            );
    }
}
