<?php

namespace App\Features\Base\Repositories;

use App\Features\User\Profiles\Models\Profile;
use App\Features\User\ProfilesRules\Models\ProfileRule;
use App\Features\User\ProfilesUsers\Models\ProfileUser;
use App\Features\User\Rules\Models\Rule;

class PolicyRepository
{
    protected function findAllByProfileUserId(string $userId)
    {
        return Rule::select(
                Rule::tableField(Rule::DESCRIPTION),
                Rule::tableField(Rule::SUBJECT),
                Rule::tableField(Rule::ACTION),
            )
            ->join(
                ProfileRule::tableName(),
                ProfileRule::tableField(ProfileRule::RULE_ID),
                Rule::tableField(Rule::ID)
            )
            ->join(
                Profile::tableName(),
                Profile::tableField(Profile::ID),
                ProfileRule::tableField(ProfileRule::PROFILE_ID),
            )
            ->join(
                ProfileUser::tableName(),
                ProfileUser::tableField(ProfileUser::PROFILE_ID),
                Profile::tableField(Profile::ID)
            )
            ->where([
                ProfileUser::tableField(ProfileUser::USER_ID) => $userId,
                Profile::tableField(Profile::ACTIVE) => true,
                Rule::tableField(Rule::ACTIVE) => true,
            ]);
    }

    public function findAllPolicyUser(string $userId)
    {
        return $this->findAllByProfileUserId($userId)
            ->groupBy(Rule::tableField(Rule::ID))
            ->get()
            ->pluck(Rule::DESCRIPTION)
            ->toArray();
    }
}
