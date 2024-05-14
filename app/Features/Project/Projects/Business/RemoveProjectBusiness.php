<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\RemoveProjectBusinessInterface;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveProjectBusiness extends Business implements RemoveProjectBusinessInterface
{
    public function __construct(
        private readonly ProjectsRepositoryInterface $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): void
    {
        $this->getPolicy()->havePermission(RulesEnum::PROJECTS_ADMIN_MASTER_DELETE->value);

        ProjectsValidations::projectExists(
            $id,
            $this->projectsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $this->projectsRepository->saveTeamUsers($id, []);

            $this->projectsRepository->remove($id);

            Transaction::commit();
        }
        catch (\Exception $exception)
        {
            Transaction::rollBack();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
