<?php

namespace Tests\Unit\Mocks;

use App\Features\Person\Persons\Models\Person;
use App\Features\Project\Projects\Models\Project;
use App\Features\User\AdminUsers\Models\AdminUser;
use App\Features\User\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\User\Profiles\Models\Profile;
use App\Features\User\Users\Models\User;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use App\Shared\Utils\Hash;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;

class UserMocks
{
    public static function findAllUsers(): Collection
    {
        return Collection::make([
            [
                User::NAME     => "UserName",
                User::EMAIL    => "email.example@email.com",
                User::PASSWORD => "$2y$10$3D5HkxDb1U1qGxldZ6Bi6eCLrmRE4U8wXoRFfm4vWCYoJP1toiRGa",
                User::ACTIVE   => true,
                User::ID       => Uuid::uuid4Generate(),
            ]
        ]);
    }

    public static function showTeamUser(
        string $id = null,
        string $profileUniqueName = null,
        Collection $projects = null,
    ): object
    {
        $idAux = !is_null($id) ? $id : Uuid::uuid4Generate();

        return (object) ([
            User::ID        => $idAux,
            User::NAME      => "UserName",
            User::EMAIL     => "email.example@email.com",
            User::PASSWORD  => "$2y$10$3D5HkxDb1U1qGxldZ6Bi6eCLrmRE4U8wXoRFfm4vWCYoJP1toiRGa",
            User::ACTIVE    => true,
            'team_user_id'  => Uuid::uuid4Generate(),
            'user' => (object) ([
                User::ID        => $idAux,
                User::NAME      => "UserName",
                User::EMAIL     => "email.example@email.com",
                User::PASSWORD  => "$2y$10$3D5HkxDb1U1qGxldZ6Bi6eCLrmRE4U8wXoRFfm4vWCYoJP1toiRGa",
                User::ACTIVE    => true,
            ]),
            'profile'      => Collection::make([
                (object) ([
                    Profile::ID    => Uuid::uuid4Generate(),
                    Profile::UNIQUE_NAME => $profileUniqueName
                ])
            ]),
            'projects' => $projects ?:Collection::make([
                (object) ([
                    Project::ID => Uuid::uuid4Generate()
                ])
            ])
        ]);
    }

    public static function getAdminUserInAuth(string $pass = null, bool $active = true): object
    {
        if(is_null($pass))
        {
            $pass = RandomStringHelper::alnumGenerate();
        }

        $userId = Uuid::uuid4Generate();

        return (object) ([
            User::NAME      => "UserName",
            User::EMAIL     => "email.example@email.com",
            User::PASSWORD  => Hash::generateHash($pass),
            User::ACTIVE    => $active,
            User::ID        => $userId,
            'adminUser'     => (object) ([
                AdminUser::ID => Uuid::uuid4Generate(),
                AdminUser::USER_ID => $userId
            ]),
            'profile' => DatabaseCollection::make([
                (object) ([
                    Profile::ID => Uuid::uuid4Generate(),
                    Profile::UNIQUE_NAME => ProfileUniqueNameEnum::ADMIN_MASTER
                ]),
            ]),
        ]);
    }

    public static function getPersonCreated(): object
    {
        return (object) ([
            Person::ID => Uuid::uuid4Generate(),
            Person::PHONE => '51999999999',
            Person::ZIP_CODE => '00000000',
            Person::ADDRESS => 'test',
            Person::NUMBER_ADDRESS => '23',
            Person::COMPLEMENT => '',
            Person::DISTRICT => 'test',
            Person::UF => 'RS',
            Person::CITY_ID => Uuid::uuid4Generate(),
        ]);
    }

    public static function getUserSaved(string $userId = null): object
    {
        return (object) ([
            User::ID        => $userId ?: Uuid::uuid4Generate(),
            User::NAME      => "UserName",
            User::EMAIL     => "email.example@email.com",
            User::PASSWORD  => "$2y$10$3D5HkxDb1U1qGxldZ6Bi6eCLrmRE4U8wXoRFfm4vWCYoJP1toiRGa",
            User::ACTIVE    => true,
        ]);
    }
}
