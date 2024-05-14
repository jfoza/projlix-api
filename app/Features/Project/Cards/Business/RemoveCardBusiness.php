<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Cards\Contracts\CardsRepositoryInterface;
use App\Features\Project\Cards\Contracts\RemoveCardBusinessInterface;
use App\Features\Project\Cards\Validations\CardsValidations;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveCardBusiness extends Business implements RemoveCardBusinessInterface
{
    private string $id;

    public function __construct(
        private readonly CardsRepositoryInterface $cardsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $id): void
    {
        $this->id = $id;

        $policy = $this->getPolicy();

        match (true)
        {
            $policy->haveRule(RulesEnum::CARDS_ADMIN_MASTER_DELETE->value),
            $policy->haveRule(RulesEnum::CARDS_PROJECT_MANAGER_DELETE->value)
                => $this->removeByAdminMasterProjectManager(),

            $policy->haveRule(RulesEnum::CARDS_TEAM_LEADER_DELETE->value),
            $policy->haveRule(RulesEnum::CARDS_PROJECT_MEMBER_DELETE->value)
                => $this->removeByTeamLeaderProjectMember(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function removeByAdminMasterProjectManager(): void
    {
        CardsValidations::cardExists(
            $this->id,
            $this->cardsRepository
        );

        $this->removeCard();
    }

    /**
     * @throws AppException
     */
    private function removeByTeamLeaderProjectMember(): void
    {
        $card = CardsValidations::cardExists(
            $this->id,
            $this->cardsRepository
        );

        $this->canAccessProjects(
            [$card->section->project_id],
            MessagesEnum::PROJECT_NOT_ALLOWED_IN_CARD->value
        );

        $this->removeCard();
    }

    /**
     * @throws AppException
     */
    private function removeCard(): void
    {
        Transaction::beginTransaction();

        try
        {
            $this->cardsRepository->remove($this->id);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollBack();

            AppException::dispatchByEnvironment($e);
        }
    }
}
