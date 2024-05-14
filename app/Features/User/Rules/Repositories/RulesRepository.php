<?php

namespace App\Features\User\Rules\Repositories;

use App\Features\Base\Repositories\PolicyRepository;
use App\Features\User\Rules\Contracts\RulesRepositoryInterface;
use App\Features\User\Rules\Models\Rule;

class RulesRepository extends PolicyRepository implements RulesRepositoryInterface
{
    public function findAllByUserId(string $userId)
    {
        return $this->findAllByProfileUserId($userId)
            ->groupBy(Rule::tableField(Rule::ID))
            ->get()
            ->toArray();
    }
}
