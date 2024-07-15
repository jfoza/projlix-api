<?php

namespace App\Features\General\Tags\Validations;

use App\Exceptions\AppException;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\Models\Tag;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class TagsValidations
{
    /**
     * @throws AppException
     */
    public static function tagExists(
        string $id,
        TagsRepositoryInterface $tagsRepository,
    ): object
    {
        if (!$result = $tagsRepository->findById($id)) {
            throw new AppException(
                MessagesEnum::TAG_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $result;
    }

    /**
     * @throws AppException
     */
    public static function tagsExists(
        array $tagsId,
        TagsRepositoryInterface $tagsRepository,
    ): Collection
    {
        $tags = $tagsRepository->findByIds($tagsId);

        $ids = $tags->pluck(Tag::ID)->toArray();

        foreach ($tagsId as $tagId)
        {
            if(!in_array($tagId, $ids))
            {
                throw new AppException(
                    MessagesEnum::TAG_NOT_FOUND->value,
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        return $tags;
    }

    /**
     * @throws AppException
     */
    public static function tagHasProjects(object $tag): void
    {
        if(count($tag->projects) > 0)
        {
            throw new AppException(
                MessagesEnum::TAG_HAS_PROJECTS_IN_DELETE,
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
