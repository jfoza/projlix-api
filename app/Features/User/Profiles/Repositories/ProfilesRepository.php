<?php
declare(strict_types=1);

namespace App\Features\User\Profiles\Repositories;

use App\Features\User\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\User\Profiles\DTO\ProfilesFiltersDTO;
use App\Features\User\Profiles\Models\Profile;
use App\Features\User\ProfileTypes\Models\ProfileType;
use Illuminate\Support\Collection;

class ProfilesRepository implements ProfilesRepositoryInterface
{
    public function findAll(ProfilesFiltersDTO $profilesFiltersDTO): Collection
    {
        $select = [
            Profile::tableField(Profile::ID),
            Profile::tableField(Profile::DESCRIPTION),
            Profile::tableField(Profile::UNIQUE_NAME),
            Profile::tableField(Profile::ACTIVE),
        ];

        return collect(
            Profile::select($select)
                ->when(
                    isset($profilesFiltersDTO->profileUniqueName),
                    fn($q) => $q->whereIn(
                        Profile::tableField(Profile::UNIQUE_NAME),
                        $profilesFiltersDTO->profileUniqueName)
                )
                ->when(
                    isset($profilesFiltersDTO->profileType),
                    fn($q) => $q
                        ->join(
                            ProfileType::tableName(),
                            ProfileType::tableField(ProfileType::ID),
                            Profile::tableField(Profile::PROFILE_TYPE_ID)
                        )
                        ->where(
                            ProfileType::tableField(ProfileType::UNIQUE_NAME),
                            $profilesFiltersDTO->profileType
                        )
                )
                ->get()
        );
    }

    public function findById(string $id): ?object
    {
        return Profile::where(Profile::ID, $id)->first();
    }

    public function findByUniqueName(string $uniqueName): ?object
    {
        return Profile::where(Profile::UNIQUE_NAME, $uniqueName)->first();
    }
}
