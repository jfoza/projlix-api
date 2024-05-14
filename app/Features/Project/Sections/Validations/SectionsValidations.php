<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Validations;

use App\Exceptions\AppException;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class SectionsValidations
{
    /**
     * @throws AppException
     */
    public static function sectionExists(
        string $id,
        SectionsRepositoryInterface $sectionsRepository
    ): object
    {
        if(!$result = $sectionsRepository->findById($id))
        {
            throw new AppException(
                MessagesEnum::SECTION_NOT_FOUND->value,
                Response::HTTP_NOT_FOUND
            );
        }

        return $result;
    }
}
