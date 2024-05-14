<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\Repositories;

use App\Features\Base\Traits\BuilderTrait;
use App\Features\Project\Cards\Contracts\CardsRepositoryInterface;
use App\Features\Project\Cards\DTO\CardDTO;
use App\Features\Project\Cards\DTO\CardFiltersDTO;
use App\Features\Project\Cards\Models\Card;
use App\Features\Project\Cards\Traits\CardsListsTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CardsRepository implements CardsRepositoryInterface
{
    use BuilderTrait, CardsListsTrait;

    public function __construct(private readonly Card $card) {}

    public function findAll(CardFiltersDTO $cardsFiltersDTO): LengthAwarePaginator|Collection
    {
        $builder = $this
            ->getBaseQueryFilters($cardsFiltersDTO)
            ->with(['tag']);

        return $this->paginateOrGet(
            $builder,
            $cardsFiltersDTO->paginationOrder
        );
    }

    public function findById(string $id): ?object
    {
        return $this
            ->getBaseQuery()
            ->with(['section'])
            ->where(
                Card::tableField(Card::ID),
                $id
            )
            ->first();
    }

    public function create(CardDTO $cardsDto): object
    {
        return $this->card->create([
            Card::CODE           => $cardsDto->code,
            Card::SECTION_ID     => $cardsDto->sectionId,
            Card::USER_ID        => $cardsDto->responsible,
            Card::TAG_PROJECT_ID => $cardsDto->tagProjectId,
            Card::DESCRIPTION    => $cardsDto->description,
            Card::LIMIT_DATE     => $cardsDto->limitDate,
            Card::STATUS         => $cardsDto->status,
        ]);
    }

    public function save(CardDTO $cardsDto): object
    {
        // TODO: Implement save() method.
    }

    public function remove(string $id): void
    {
        $this->card->where(Card::ID, $id)->delete();
    }
}
