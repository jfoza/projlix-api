<?php
declare(strict_types=1);

namespace App\Features\General\Tags\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\Contracts\UpdateStatusTagBusinessInterface;
use App\Features\General\Tags\Models\Tag;
use App\Features\General\Tags\Validations\TagsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateStatusTagBusiness extends Business implements UpdateStatusTagBusinessInterface
{
    public function __construct(
        private readonly TagsRepositoryInterface $tagsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::TAGS_UPDATE->value);

        $tag = TagsValidations::tagExists(
            $id,
            $this->tagsRepository,
        );

        $newStatus = !$tag->active;

        Transaction::beginTransaction();

        try
        {
            $this->tagsRepository->saveStatus(
                $tag->id,
                $newStatus
            );

            Transaction::commit();

            return (object) ([Tag::ID => $tag->id, Tag::ACTIVE => $newStatus]);
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
