<?php

namespace App\Features\User\Rules\Contracts;

interface RulesRepositoryInterface
{
    public function findAllByUserId(string $userId);
}
