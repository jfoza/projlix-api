<?php

namespace App\Features\User\TeamUsers\Traits;

use App\Features\Project\Projects\Models\Project;
use App\Features\User\Profiles\Models\Profile;
use App\Features\User\TeamUsers\Models\TeamUser;
use App\Features\User\Users\DTO\UsersFiltersDTO;
use App\Features\User\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HigherOrderWhenProxy;

trait TeamUsersTrait
{
    public function getBaseQuery(): Builder
    {
        $select = [
            TeamUser::tableField(TeamUser::ID),
            TeamUser::tableField(TeamUser::ID).' AS team_user_id',
            TeamUser::tableField(TeamUser::USER_ID),
        ];

        return TeamUser::with(['user.profile'])
            ->select($select)
            ->join(
                User::tableName(),
                User::tableField(User::ID),
                TeamUser::tableField(TeamUser::USER_ID)
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
            )
            ->when(
                isset($usersFiltersDTO->profileUniqueName),
                fn($aux) => $aux->whereHas(
                    'user.profile',
                    fn($q) => $q->whereIn(
                        Profile::tableField(Profile::UNIQUE_NAME),
                        $usersFiltersDTO->profileUniqueName
                    )
                )
            )
            ->when(
                isset($usersFiltersDTO->projectsId),
                fn($aux) => $aux->whereHas(
                    'projects',
                    fn($q) => $q->whereIn(
                        Project::tableField(Project::ID),
                        $usersFiltersDTO->projectsId
                    )
                )
            );
    }
}
