<?php
declare(strict_types=1);

namespace App\Features\General\Tags\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Tags\Contracts\CreateTagBusinessInterface;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\DTO\TagsDTO;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateTagBusiness extends Business implements CreateTagBusinessInterface
{
    public function __construct(
        private readonly TagsRepositoryInterface $tagsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function handle(TagsDTO $tagsDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::TAGS_INSERT->value);

        Transaction::beginTransaction();

        try
        {
            $result = $this->tagsRepository->create($tagsDTO);

            Transaction::commit();

            return $result;
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
