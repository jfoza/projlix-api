<?php
declare(strict_types=1);

namespace App\Features\General\Tags\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Tags\Contracts\ShowTagBusinessInterface;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\Validations\TagsValidations;
use App\Shared\Enums\RulesEnum;

class ShowTagBusiness extends Business implements ShowTagBusinessInterface
{
    public function __construct(
        private readonly TagsRepositoryInterface $tagsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::TAGS_VIEW->value);

        return TagsValidations::tagExists(
            $id,
            $this->tagsRepository,
        );
    }
}
