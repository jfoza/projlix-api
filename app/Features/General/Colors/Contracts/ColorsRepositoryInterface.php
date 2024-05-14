<?php
declare(strict_types=1);

namespace App\Features\General\Colors\Contracts;

use Illuminate\Support\Collection;

interface ColorsRepositoryInterface
{
    public function findAll(): Collection;
    public function findById(string $id): ?object;
}
