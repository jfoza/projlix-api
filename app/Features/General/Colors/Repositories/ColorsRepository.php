<?php
declare(strict_types=1);

namespace App\Features\General\Colors\Repositories;

use App\Features\General\Colors\Contracts\ColorsRepositoryInterface;
use App\Features\General\Colors\Models\Color;
use Illuminate\Support\Collection;

class ColorsRepository implements ColorsRepositoryInterface
{
    public function findAll(): Collection
    {
        return collect(Color::get());
    }

    public function findById(string $id): ?object
    {
        return Color::where(Color::ID, $id)->first();
    }
}
