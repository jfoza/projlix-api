<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\Project\Sections\Business\CreateSectionBusiness;
use App\Features\Project\Sections\Business\FindAllSectionsBusiness;
use App\Features\Project\Sections\Business\RemoveSectionBusiness;
use App\Features\Project\Sections\Business\SectionReorderingBusiness;
use App\Features\Project\Sections\Business\ShowSectionBusiness;
use App\Features\Project\Sections\Business\UpdateSectionBusiness;
use App\Features\Project\Sections\Contracts\CreateSectionBusinessInterface;
use App\Features\Project\Sections\Contracts\FindAllSectionsBusinessInterface;
use App\Features\Project\Sections\Contracts\RemoveSectionBusinessInterface;
use App\Features\Project\Sections\Contracts\SectionReorderingBusinessInterface;
use App\Features\Project\Sections\Contracts\SectionsRepositoryInterface;
use App\Features\Project\Sections\Contracts\ShowSectionBusinessInterface;
use App\Features\Project\Sections\Contracts\UpdateSectionBusinessInterface;
use App\Features\Project\Sections\Repositories\SectionsRepository;

class SectionsProviders extends ServiceProvider
{
    public array $bindings = [
        SectionsRepositoryInterface::class  => SectionsRepository::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->bind(
            FindAllSectionsBusinessInterface::class,
            FindAllSectionsBusiness::class
        );

        $this->bind(
            ShowSectionBusinessInterface::class,
            ShowSectionBusiness::class
        );

        $this->bind(
            CreateSectionBusinessInterface::class,
            CreateSectionBusiness::class
        );

        $this->bind(
            UpdateSectionBusinessInterface::class,
            UpdateSectionBusiness::class
        );

        $this->bind(
            RemoveSectionBusinessInterface::class,
            RemoveSectionBusiness::class
        );

        $this->bind(
            SectionReorderingBusinessInterface::class,
            SectionReorderingBusiness::class
        );
    }
}
