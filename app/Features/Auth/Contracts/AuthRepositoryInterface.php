<?php

namespace App\Features\Auth\Contracts;

use App\Features\Auth\DTO\AuthDTO;
use Illuminate\Database\Eloquent\Collection;

interface AuthRepositoryInterface
{
    public function findByUserId(string $userId): Collection|array;
    public function findByUserIdAndDates(string $userId, string $initialDate, string $finalDate): Collection|array;
    public function inactivateAll(string $userId): void;
    public function create(AuthDTO $authDTO);
}
