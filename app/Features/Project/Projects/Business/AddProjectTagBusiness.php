<?php

namespace App\Features\Project\Projects\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\Validations\TagsValidations;
use App\Features\Project\Projects\Contracts\ProjectsRepositoryInterface;
use App\Features\Project\Projects\Contracts\AddProjectTagBusinessInterface;
use App\Features\Project\Projects\DTO\ProjectDTO;
use App\Features\Project\Projects\Validations\ProjectsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class AddProjectTagBusiness extends Business implements AddProjectTagBusinessInterface
{
    private ProjectDTO $projectDTO;

    public function __construct(
        private readonly TagsRepositoryInterface     $tagsRepository,
        private readonly ProjectsRepositoryInterface $projectsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(ProjectDTO $projectDTO): void
    {
        $this->projectDTO = $projectDTO;

        $policy = $this->getPolicy();

        match (true)
        {
            $policy->haveRule(RulesEnum::PROJECTS_ADMIN_MASTER_TAGS_INSERT->value) => true,

            $policy->haveRule(RulesEnum::PROJECTS_PROJECT_MANAGER_TAGS_INSERT->value),
            $policy->haveRule(RulesEnum::PROJECTS_TEAM_LEADER_TAGS_INSERT->value) => function() {
                $this->canAccessProjects([$this->projectDTO->id]);
            },

            default => $policy->dispatchForbiddenError(),
        };

        $this->addProjectTag();
    }

    /**
     * @throws AppException
     */
    private function addProjectTag(): void
    {
        ProjectsValidations::projectExists(
            $this->projectDTO->id,
            $this->projectsRepository
        );

        TagsValidations::tagExists(
            $this->projectDTO->tagId,
            $this->tagsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $this->projectsRepository->saveTags(
                $this->projectDTO->id,
                [$this->projectDTO->tagId],
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
