<?php

namespace App\Features\User\Users\Contracts;

use App\Features\User\Users\DTO\UserDTO;

interface UsersRepositoryInterface
{
    public function findByEmail(string $email): ?object;
    public function findById(string $id, bool $withRelations = false): ?object;
    public function create(UserDTO $userDTO): object;
    public function update(UserDTO $userDTO): object;
    public function savePassword(string $userId, string $password): void;
    public function saveProfiles(string $userId, array $profiles): void;
    public function saveStatus(string $userId, bool $status): void;
}
