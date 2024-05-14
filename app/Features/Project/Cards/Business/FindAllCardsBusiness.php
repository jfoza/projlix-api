<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Project\Cards\Contracts\CardsRepositoryInterface;
use App\Features\Project\Cards\Contracts\FindAllCardsBusinessInterface;
use App\Features\Project\Cards\DTO\CardFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllCardsBusiness extends Business implements FindAllCardsBusinessInterface
{
    private CardFiltersDTO $cardsFiltersDTO;

    public function __construct(
        private readonly CardsRepositoryInterface $cardsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(CardFiltersDTO $cardsFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->cardsFiltersDTO = $cardsFiltersDTO;

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

    private function listByAdminMasterProjectManager(): Collection
    {
        return $this->cardsRepository->findAll($this->cardsFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function listByTeamLeaderProjectMember(): Collection
    {
        if(count($this->cardsFiltersDTO->projectsId) > 0)
        {
            $this->canAccessEachProject(
                $this->cardsFiltersDTO->projectsId
            );
        }
        else
        {
            $this->cardsFiltersDTO->projectsId = $this->getTeamUserProjectsId();
        }

        return $this->cardsRepository->findAll($this->cardsFiltersDTO);
    }
}
