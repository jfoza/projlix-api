<?php
declare(strict_types=1);

namespace App\Features\General\Icons\Repositories;

use App\Features\General\Icons\Contracts\IconsRepositoryInterface;
use App\Features\General\Icons\Models\Icon;
use Illuminate\Support\Collection;

class IconsRepository implements IconsRepositoryInterface
{
    public function findAll(): Collection
    {
        return collect(Icon::get());
    }

    public function findById(string $id): ?object
    {
        return Icon::where(Icon::ID, $id)->first();
    }
}
