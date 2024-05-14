<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\Validations;

use App\Exceptions\AppException;
use App\Features\Project\Cards\Contracts\CardsRepositoryInterface;
use App\Features\Project\Projects\Models\Project;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class CardsValidations
{
    /**
     * @throws AppException
     */
    public static function cardExists(
        string $id,
        CardsRepositoryInterface $cardsRepository,
    ): object
    {
        if(!$card = $cardsRepository->findById($id))
        {
            throw new AppException(
                MessagesEnum::CARD_NOT_FOUND->value,
                Response::HTTP_NOT_FOUND
            );
        }

        return $card;
    }

    /**
     * @throws AppException
     */
    public static function projectTagExists(object $tag, string $projectId): object
    {
        $haystack = collect($tag->projects);

        if(!$needle = $haystack->firstWhere(Project::ID, $projectId))
        {
            throw new AppException(
                MessagesEnum::TAG_NOT_BELONGS_TO_PROJECT,
                Response::HTTP_BAD_REQUEST
            );
        }

        return $needle->pivot;
    }

    /**
     * @throws AppException
     */
    public static function validateUserExistsById(
        string $userId,
        UsersRepositoryInterface $usersRepository
    ): object
    {
        if(!$user = $usersRepository->findById($userId, true))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $user;
    }

    /**
     * @throws AppException
     */
    public static function validateTeamUserAccessToProject(object $user, string $projectId): void
    {
        if(!$projects = $user->teamUser->projects)
        {
            return;
        }

        if(!collect($projects)->firstWhere(Project::ID, $projectId))
        {
            throw new AppException(
                MessagesEnum::TEAM_USER_NOT_BELONGS_TO_PROJECT,
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
