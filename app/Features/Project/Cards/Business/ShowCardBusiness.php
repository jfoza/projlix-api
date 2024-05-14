<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Cards\Contracts\CardsRepositoryInterface;
use App\Features\Project\Cards\Contracts\ShowCardBusinessInterface;
use App\Features\Project\Cards\Validations\CardsValidations;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;

class ShowCardBusiness extends Business implements ShowCardBusinessInterface
{
    private string $id;

    public function __construct(
        private readonly CardsRepositoryInterface $cardsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): object
    {
        $this->id = $id;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::CARDS_ADMIN_MASTER_VIEW->value),
            $policy->haveRule(RulesEnum::CARDS_PROJECT_MANAGER_VIEW->value) => $this->listByAdminMasterProjectManager(),

            $policy->haveRule(RulesEnum::CARDS_TEAM_LEADER_VIEW->value),
            $policy->haveRule(RulesEnum::CARDS_PROJECT_MEMBER_VIEW->value) => $this->listByTeamLeaderProjectMember(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function listByAdminMasterProjectManager(): object
    {
        return CardsValidations::cardExists(
            $this->id,
            $this->cardsRepository
        );
    }

    /**
     * @throws AppException
     */
    private function listByTeamLeaderProjectMember(): object
    {
        $card = CardsValidations::cardExists(
            $this->id,
            $this->cardsRepository
        );

        $this->canAccessProjects(
            [$card->section->project_id],
            MessagesEnum::PROJECT_NOT_ALLOWED_IN_CARD->value
        );

        return $card;
    }
}
