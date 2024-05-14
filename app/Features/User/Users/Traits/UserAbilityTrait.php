<?php

namespace App\Features\User\Users\Traits;


use App\Features\User\Rules\Contracts\RulesRepositoryInterface;

trait UserAbilityTrait
{
    public function findAllUserAbility(
        mixed $user,
        RulesRepositoryInterface $rulesRepository
    )
    {
        return $rulesRepository->findAllByUserId($user->id);
    }
}
