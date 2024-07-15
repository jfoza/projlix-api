<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\ProjectUpdateAccessServiceInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectInfoBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Shared\Utils\Transaction;

class UpdateProjectInfoBusiness extends Business implements UpdateProjectInfoBusinessInterface
{
    public function __construct(
        private readonly ProjectUpdateAccessServiceInterface $projectUpdateAccessService,
        private readonly ProjectsRepositoryInterface         $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(ProjectDTO $projectDTO): object
    {
        $this->projectUpdateAccessService->execute($projectDTO->id);

        ProjectsValidations::projectExistsByNameInUpdate(
            $projectDTO->id,
            $projectDTO->name,
            $this->projectsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $result = $this->projectsRepository->save($projectDTO);

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
