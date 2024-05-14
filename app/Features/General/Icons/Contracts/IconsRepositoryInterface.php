<?php
declare(strict_types=1);

namespace App\Features\General\Icons\Contracts;

use Illuminate\Support\Collection;

interface IconsRepositoryInterface
{
    public function findAll(): Collection;
    public function findById(string $id): ?object;
}
