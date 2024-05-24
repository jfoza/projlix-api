<?php
declare(strict_types=1);

namespace App\Features\User\Profiles\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\User\Profiles\Contracts\FindAllProfilesBusinessInterface;
use App\Features\User\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\User\Profiles\DTO\ProfilesFiltersDTO;
use App\Features\User\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;

class FindAllProfilesBusiness extends Business implements FindAllProfilesBusinessInterface
{
    private ProfilesFiltersDTO $profilesFiltersDTO;

    public function __construct(
        private readonly ProfilesRepositoryInterface $profilesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(ProfilesFiltersDTO $profilesFiltersDTO): Collection
    {
        $this->profilesFiltersDTO = $profilesFiltersDTO;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::PROFILES_ADMIN_MASTER_VIEW->value)    => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::PROFILES_PROJECT_MANAGER_VIEW->value) => $this->findByProjectManager(),
            $policy->haveRule(RulesEnum::PROFILES_TEAM_LEADER_VIEW->value)     => $this->findByTeamLeader(),
            $policy->haveRule(RulesEnum::PROFILES_PROJECT_MEMBER_VIEW->value)  => $this->findByProjectMember(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    private function findByAdminMaster(): Collection
    {
        $this->profilesFiltersDTO->profileUniqueName = ProfileUniqueNameEnum::PROFILES_BY_ADMIN_MASTER;

        return $this->profilesRepository->findAll($this->profilesFiltersDTO);
    }

    private function findByProjectManager(): Collection
    {
        $this->profilesFiltersDTO->profileUniqueName = ProfileUniqueNameEnum::PROFILES_BY_PROJECT_MANAGER;

        return $this->profilesRepository->findAll($this->profilesFiltersDTO);
    }

    private function findByTeamLeader(): Collection
    {
        $this->profilesFiltersDTO->profileUniqueName = ProfileUniqueNameEnum::PROFILES_BY_TEAM_LEADER;

        return $this->profilesRepository->findAll($this->profilesFiltersDTO);
    }

    private function findByProjectMember(): Collection
    {
        $this->profilesFiltersDTO->profileUniqueName = ProfileUniqueNameEnum::PROFILES_BY_PROJECT_MEMBER;

        return $this->profilesRepository->findAll($this->profilesFiltersDTO);
    }
}
