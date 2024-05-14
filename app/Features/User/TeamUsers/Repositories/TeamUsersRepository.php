<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Repositories;

use App\Features\Base\Traits\BuilderTrait;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Features\User\TeamUsers\Models\TeamUser;
use App\Features\User\TeamUsers\Traits\TeamUsersTrait;
use App\Features\User\Users\DTO\UsersFiltersDTO;
use App\Features\User\Users\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TeamUsersRepository implements TeamUsersRepositoryInterface
{
    use TeamUsersTrait, BuilderTrait;

    public function __construct(
        private readonly TeamUser $teamUser,
    ) {}

    public function findAll(UsersFiltersDTO $usersFiltersDTO): LengthAwarePaginator|Collection
    {
        $builder = $this
            ->getBaseQueryFilters($usersFiltersDTO)
            ->with(['projects']);

        return $this->paginateOrGet(
            $builder,
            $usersFiltersDTO->paginationOrder
        );
    }

    public function findByUserId(string $userId): ?object
    {
        return $this
            ->getBaseQuery()
            ->where(User::tableField(User::ID), $userId)
            ->first();
    }

    public function findByTeamUsersIds(array $ids): Collection
    {
        return collect(
            $this
                ->getBaseQuery()
                ->with(['projects'])
                ->whereIn(TeamUser::tableField(TeamUser::ID), $ids)
                ->get()
        );
    }

    public function create(string $userId): object
    {
        return $this->teamUser->create([TeamUser::USER_ID => $userId]);
    }

    public function saveProjects(string $teamUserId, array $projectsId): void
    {
        TeamUser::find($teamUserId)->projects()->sync($projectsId);
    }
}