<?php

namespace App\Features\User\TeamUsers\Traits;

use App\Features\Project\Projects\Models\Project;
use App\Features\User\Profiles\Models\Profile;
use App\Features\User\TeamUsers\DTO\TeamUsersFiltersDTO;
use App\Features\User\TeamUsers\Models\TeamUser;
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
            User::tableField(User::ACTIVE),
        ];

        return TeamUser::with(['user.profile', 'projects'])
            ->select($select)
            ->join(
                User::tableName(),
                User::tableField(User::ID),
                TeamUser::tableField(TeamUser::USER_ID)
            );
    }

    public function getBaseQueryFilters(
        TeamUsersFiltersDTO $teamUsersFiltersDTO
    ): Builder|HigherOrderWhenProxy
    {
        return $this
            ->getBaseQuery()
            ->when(
                isset($teamUsersFiltersDTO->name),
                fn($aux) => $aux->where(
                    User::tableField(User::NAME),
                    'ILIKE',
                    '%'.$teamUsersFiltersDTO->name.'%'
                )
            )
            ->when(
                isset($teamUsersFiltersDTO->email),
                fn($aux) => $aux->where(
                    User::tableField(User::EMAIL),
                    $teamUsersFiltersDTO->email
                )
            )
            ->when(
                isset($teamUsersFiltersDTO->active),
                fn($aux) => $aux->whereHas(
                    'user',
                    fn($user) => $user->where(
                        User::tableField(User::ACTIVE),
                        $teamUsersFiltersDTO->active
                    )
                )
            )
            ->when(
                isset($teamUsersFiltersDTO->profileUniqueName),
                fn($aux) => $aux->whereHas(
                    'user.profile',
                    fn($q) => $q->whereIn(
                        Profile::tableField(Profile::UNIQUE_NAME),
                        $teamUsersFiltersDTO->profilesUniqueName
                    )
                )
            )
            ->when(
                isset($teamUsersFiltersDTO->profileId),
                fn($aux) => $aux->whereHas(
                    'user.profile',
                    fn($profile) => $profile->where(
                        Profile::tableField(Profile::ID),
                        $teamUsersFiltersDTO->profileId
                    )
                )
            )
            ->when(
                !empty($teamUsersFiltersDTO->projectsId),
                fn($aux) => $aux->whereHas(
                    'projects',
                    fn($project) => $project->whereIn(
                        Project::tableField(Project::ID),
                        $teamUsersFiltersDTO->projectsId
                    )
                )
            );
    }
}
