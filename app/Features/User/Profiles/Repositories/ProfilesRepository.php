<?php
declare(strict_types=1);

namespace App\Features\User\Profiles\Repositories;

use App\Features\User\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\User\Profiles\Models\Profile;

class ProfilesRepository implements ProfilesRepositoryInterface
{
    public function findById(string $id): ?object
    {
        return Profile::where(Profile::ID, $id)->first();
    }

    public function findByUniqueName(string $uniqueName): ?object
    {
        return Profile::where(Profile::UNIQUE_NAME, $uniqueName)->first();
    }
}
