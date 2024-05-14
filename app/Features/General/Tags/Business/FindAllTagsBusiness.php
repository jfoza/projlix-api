<?php
declare(strict_types=1);

namespace App\Features\General\Tags\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Tags\Contracts\FindAllTagsBusinessInterface;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;

class FindAllTagsBusiness extends Business implements FindAllTagsBusinessInterface
{
    public function __construct(
        private readonly TagsRepositoryInterface $tagsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(): Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::TAGS_VIEW->value);

        return $this->tagsRepository->findAll();
    }
}
