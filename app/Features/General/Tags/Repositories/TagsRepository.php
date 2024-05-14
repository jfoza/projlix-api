<?php
declare(strict_types=1);

namespace App\Features\General\Tags\Repositories;

use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\DTO\TagsDTO;
use App\Features\General\Tags\Models\Tag;
use App\Features\Project\ProjectTag\Models\ProjectTag;
use Illuminate\Support\Collection;

class TagsRepository implements TagsRepositoryInterface
{
    public function __construct(
        private readonly Tag $tag,
    ) {}

    public function findAll(): Collection
    {
        return collect(Tag::with(['projects'])->get());
    }

    public function findById(string $id): ?object
    {
        return Tag::with(['projects'])->where(Tag::ID, $id)->first();
    }

    public function create(TagsDTO $tagsDTO): object
    {
        return $this->tag->create([
            Tag::NAME => $tagsDTO->name,
            Tag::ACTIVE => true
        ]);
    }

    public function save(TagsDTO $tagsDTO): object
    {
        $updated = [
            Tag::ID   => $tagsDTO->id,
            Tag::NAME => $tagsDTO->name,
        ];

        $this->tag->where(Tag::ID, $tagsDTO->id)->update($updated);

        return (object) $updated;
    }

    public function saveStatus(string $id, bool $status): void
    {
        $this->tag->where(Tag::ID, $id)->update([
            Tag::ACTIVE => $status
        ]);
    }

    public function remove(string $id): void
    {
        $this->tag->where(Tag::ID, $id)->delete();
    }
}
