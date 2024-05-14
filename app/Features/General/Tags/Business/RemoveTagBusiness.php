<?php
declare(strict_types=1);

namespace App\Features\General\Tags\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Tags\Contracts\RemoveTagBusinessInterface;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\Validations\TagsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveTagBusiness extends Business implements RemoveTagBusinessInterface
{
    public function __construct(
        private readonly TagsRepositoryInterface $tagsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): void
    {
        $this->getPolicy()->havePermission(RulesEnum::TAGS_DELETE->value);

        $tag = TagsValidations::tagExists(
            $id,
            $this->tagsRepository,
        );

        TagsValidations::tagHasProjects($tag);

        Transaction::beginTransaction();

        try
        {
            $this->tagsRepository->remove($id);

            Transaction::commit();
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
