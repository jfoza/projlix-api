<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\Business;

use App\Features\Base\Business\Business;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\Project\Cards\Contracts\CardsRepositoryInterface;
use App\Features\Project\Cards\Contracts\UpdateCardBusinessInterface;
use App\Features\Project\Cards\DTO\CardDTO;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\User\Users\Contracts\UsersRepositoryInterface;

class UpdateCardBusiness extends Business implements UpdateCardBusinessInterface
{
    private CardDTO $cardsDto;
    private object $section;

    public function __construct(
        private readonly CardsRepositoryInterface    $cardsRepository,
        private readonly SectionsRepositoryInterface $sectionsRepository,
        private readonly TagsRepositoryInterface     $tagsRepository,
        private readonly UsersRepositoryInterface    $usersRepository,
    ) {}

    public function handle(CardDTO $cardsDto): object
    {
        // TODO: Implement handle() method.
    }
}
