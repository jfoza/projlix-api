<?php

namespace App\Features\Auth\Repositories;

use App\Features\Auth\Contracts\AuthRepositoryInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Models\Auth;
use Illuminate\Database\Eloquent\Collection;

class AuthRepository implements AuthRepositoryInterface
{
    public function findByUserId(string $userId): Collection|array
    {
        return Auth::with('user')
            ->where(Auth::USER_ID, $userId)
            ->get();
    }

    public function findByUserIdAndDates(
        string $userId,
        string $initialDate,
        string $finalDate
    ): Collection|array
    {
        return Auth::with('user')
            ->where(Auth::USER_ID, $userId)
            ->whereBetween(Auth::INITIAL_DATE, [$initialDate, $finalDate])
            ->get();
    }

    public function inactivateAll(string $userId): void
    {
        Auth::where(Auth::USER_ID, $userId)->update([Auth::ACTIVE => false]);
    }

    public function create(AuthDTO $authDTO)
    {
        return Auth::create([
            Auth::USER_ID      => $authDTO->userId,
            Auth::INITIAL_DATE => $authDTO->initialDate,
            Auth::FINAL_DATE   => $authDTO->finalDate,
            Auth::TOKEN        => $authDTO->token,
            Auth::IP_ADDRESS   => $authDTO->ipAddress,
            Auth::AUTH_TYPE    => $authDTO->authType,
        ]);
    }
}
