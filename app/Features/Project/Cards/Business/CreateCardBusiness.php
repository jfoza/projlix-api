<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\Validations\TagsValidations;
use App\Features\Project\Cards\Contracts\CardsRepositoryInterface;
use App\Features\Project\Cards\Contracts\CreateCardBusinessInterface;
use App\Features\Project\Cards\DTO\CardDTO;
use App\Features\Project\Cards\Validations\CardsValidations;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\Validations\SectionsValidations;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Shared\Enums\RulesEnum;
use App\Shared\Enums\StatusCardEnum;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Utils\Transaction;

class CreateCardBusiness extends Business implements CreateCardBusinessInterface
{
    private CardDTO $cardsDto;
    private object $section;

    public function __construct(
        private readonly CardsRepositoryInterface    $cardsRepository,
        private readonly SectionsRepositoryInterface $sectionsRepository,
        private readonly TagsRepositoryInterface     $tagsRepository,
        private readonly UsersRepositoryInterface    $usersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(CardDTO $cardsDto): object
    {
        $this->cardsDto = $cardsDto;

        $this->cardsDto->status = StatusCardEnum::OPENED->value;
        $this->cardsDto->code   = RandomStringHelper::alnumGenerate();

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::CARDS_ADMIN_MASTER_VIEW->value),
            $policy->haveRule(RulesEnum::CARDS_PROJECT_MANAGER_VIEW->value)
                => $this->createByAdminMasterProjectManager(),

            $policy->haveRule(RulesEnum::CARDS_TEAM_LEADER_VIEW->value),
            $policy->haveRule(RulesEnum::CARDS_PROJECT_MEMBER_VIEW->value)
                => $this->createByTeamLeaderProjectMember(),

        };
    }

    /**
     * @throws AppException
     */
    private function createByAdminMasterProjectManager(): object
    {
        $this->handleValidations();

        return $this->createCard();
    }

    /**
     * @throws AppException
     */
    private function createByTeamLeaderProjectMember(): object
    {
        $this->handleValidations();

        $this->canAccessProjects($this->section->project_id);

        return $this->createCard();
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        $this->section = SectionsValidations::sectionExists(
            $this->cardsDto->sectionId,
            $this->sectionsRepository
        );

        $user = CardsValidations::validateUserExistsById(
            $this->cardsDto->responsible,
            $this->usersRepository
        );

        CardsValidations::validateTeamUserAccessToProject(
            $user,
            $this->section->project_id,
        );

        $tag = TagsValidations::tagExists(
            $this->cardsDto->tagId,
            $this->tagsRepository
        );

        $projectTag = CardsValidations::projectTagExists(
            $tag,
            $this->section->project_id
        );

        $this->cardsDto->tagProjectId = $projectTag->id;
    }

    /**
     * @throws AppException
     */
    private function createCard(): object
    {
        Transaction::beginTransaction();

        try
        {
            $cardCreated = $this->cardsRepository->create($this->cardsDto);

            Transaction::commit();

            return $cardCreated;
        }
        catch (\Exception $exception)
        {
            Transaction::rollback();

            AppException::dispatchByEnvironment($exception);
        }
    }
}
