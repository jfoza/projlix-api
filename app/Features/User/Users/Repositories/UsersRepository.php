<?php
declare(strict_types=1);

namespace App\Features\User\Users\Repositories;

use App\Features\User\Users\Contracts\UsersRepositoryInterface;
use App\Features\User\Users\DTO\UserDTO;
use App\Features\User\Users\Models\User;

class UsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private readonly User $user,
    ) {}

    public function findByEmail(string $email): ?object
    {
        return User::with(['adminUser', 'profile'])
            ->where(User::EMAIL, $email)
            ->first();
    }

    public function findById(string $id, bool $withRelations = false): ?object
    {
        $relations = [];

        if($withRelations)
        {
            $relations = [
                'teamUser.projects',
                'adminUser'
            ];
        }

        return User::with($relations)->where(User::ID, $id)->first();
    }

    public function create(UserDTO $userDTO): object
    {
        return $this->user->create([
            User::PERSON_ID  => $userDTO->personId,
            User::NAME       => $userDTO->name,
            User::SHORT_NAME => $userDTO->shortName,
            User::EMAIL      => $userDTO->email,
            User::PASSWORD   => $userDTO->password,
            User::ACTIVE     => true,
        ]);
    }

    public function update(UserDTO $userDTO): object
    {
        $update = [
            User::ID    => $userDTO->id,
            User::NAME  => $userDTO->name,
            User::EMAIL => $userDTO->email,
        ];

        $this
            ->user
            ->where(User::ID, $userDTO->id)
            ->update($update);

        return (object) $update;
    }

    public function savePassword(string $userId, string $password): void
    {
        $this
            ->user
            ->where(User::ID, $userId)
            ->update([User::PASSWORD => $password]);
    }

    public function saveProfiles(string $userId, array $profiles): void
    {
        User::find($userId)->profile()->sync($profiles);
    }

    public function saveStatus(string $userId, bool $status): void
    {
        User::where(User::ID, $userId)->update([User::ACTIVE => $status]);
    }
}
