<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Validations;

use App\Exceptions\AppException;
use App\Features\User\TeamUsers\Contracts\TeamUsersRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class TeamUsersValidations
{
    /**
     * @throws AppException
     */
    public static function teamUsersExists(
        array $teamUsersId,
        TeamUsersRepositoryInterface $teamUsersRepository,
    ): Collection
    {
        $teamUsers = $teamUsersRepository->findByTeamUsersIds($teamUsersId);

        $ids = $teamUsers->pluck('team_user_id')->toArray();

        foreach ($teamUsersId as $teamUserId)
        {
            if(!in_array($teamUserId, $ids))
            {
                throw new AppException(
                    MessagesEnum::USER_NOT_FOUND,
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        return $teamUsers;
    }

    /**
     * @throws AppException
     */
    public static function teamUserExistsByUserId(
        string $userId,
        TeamUsersRepositoryInterface $teamUsersRepository
    ): object
    {
        if(!$teamUser = $teamUsersRepository->findByUserId($userId))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $teamUser;
    }
}
