<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\Models\Tag;
use App\Features\General\Tags\Validations\TagsValidations;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\ProjectUpdateAccessServiceInterface;
use App\Features\Project\Projects\Contracts\AddProjectTagBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Shared\Utils\Transaction;

class AddProjectTagBusiness extends Business implements AddProjectTagBusinessInterface
{
    public function __construct(
        private readonly ProjectUpdateAccessServiceInterface $projectUpdateAccessService,
        private readonly TagsRepositoryInterface             $tagsRepository,
        private readonly ProjectsRepositoryInterface         $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(ProjectDTO $projectDTO): void
    {
        $this->projectUpdateAccessService->execute($projectDTO->id);

        TagsValidations::tagExists(
            $projectDTO->tagId,
            $this->tagsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $this->projectsRepository->saveTags(
                $projectDTO->id,
                [$projectDTO->tagId],
            );

            Transaction::commit();
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
