<?php

namespace App\Features\Base\Traits;

use App\Features\Base\ACL\Policy;
use App\Features\Base\Cache\PolicyCache;
use App\Features\Base\Repositories\PolicyRepository;
use App\Shared\Utils\Auth;

trait PolicyGenerationTrait
{
    public function generatePolicyUser(): Policy
    {
        $rules = [];

        if($user = Auth::getUser()) {
            $rules = $this->getUserRules($user);
        }

        return new Policy($rules);
    }

    public function getUserRules(mixed $user): mixed
    {
        return PolicyCache::rememberPolicy(
            $user->id,
            function() use($user) {
                $policyRepository = new PolicyRepository();

                return $policyRepository->findAllPolicyUser($user->id);
            }
        );
    }
}
