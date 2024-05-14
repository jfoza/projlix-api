<?php

namespace App\Features\Project\Cards\Traits;

use App\Features\Project\Cards\DTO\CardFiltersDTO;
use App\Features\Project\Cards\Models\Card;
use App\Features\Project\Projects\Models\Project;
use App\Features\Project\Sections\Models\Section;

trait CardsListsTrait
{
    public function getBaseQuery()
    {
        $select = [
            Card::tableField(Card::ID),
            Card::tableField(Card::CODE),
            Card::tableField(Card::SECTION_ID),
            Card::tableField(Card::USER_ID),
            Card::tableField(Card::TAG_PROJECT_ID),
            Card::tableField(Card::DESCRIPTION),
            Card::tableField(Card::LIMIT_DATE),
            Card::tableField(Card::STATUS),
            Card::tableField(Card::CREATED_AT),
        ];

        return Card::select($select)
            ->join(
                Section::tableName(),
                Section::tableField(Section::ID),
                Card::tableField(Card::SECTION_ID)
            )
            ->join(
                Project::tableName(),
                Project::tableField(Project::ID),
                Section::tableField(Section::PROJECT_ID)
            );
    }

    public function getBaseQueryFilters(CardFiltersDTO $cardsFiltersDTO)
    {
        return $this
            ->getBaseQuery()
            ->when(
                count($cardsFiltersDTO->projectsId) > 0,
                fn($q) => $q->whereIn(
                    Project::tableField(Project::ID),
                    $cardsFiltersDTO->projectsId
                )
            )
            ;
    }
}
