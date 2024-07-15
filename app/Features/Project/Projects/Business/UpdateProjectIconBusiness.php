<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\General\Icons\Validations\IconsValidations;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\ProjectUpdateAccessServiceInterface;
use App\Features\Project\Projects\Contracts\UpdateProjectIconBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Shared\Utils\Transaction;

class UpdateProjectIconBusiness extends Business implements UpdateProjectIconBusinessInterface
{
    public function __construct(
        private readonly ProjectUpdateAccessServiceInterface $projectUpdateAccessService,
        private readonly IconsRepositoryInterface            $iconsRepository,
        private readonly ProjectsRepositoryInterface         $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(ProjectDTO $projectDTO): void
    {
        $this->projectUpdateAccessService->execute($projectDTO->id);

        IconsValidations::iconExists(
            $projectDTO->iconId,
            $this->iconsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $this->projectsRepository->saveIcon(
                $projectDTO->id,
                $projectDTO->iconId,
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
