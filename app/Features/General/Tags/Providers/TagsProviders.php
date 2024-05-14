<?php
declare(strict_types=1);

namespace App\Features\General\Tags\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\General\Tags\Business\CreateTagBusiness;
use App\Features\General\Tags\Business\FindAllTagsBusiness;
use App\Features\General\Tags\Business\RemoveTagBusiness;
use App\Features\General\Tags\Business\ShowTagBusiness;
use App\Features\General\Tags\Business\UpdateStatusTagBusiness;
use App\Features\General\Tags\Business\UpdateTagBusiness;
use App\Features\General\Tags\Contracts\CreateTagBusinessInterface;
use App\Features\General\Tags\Contracts\FindAllTagsBusinessInterface;
use App\Features\General\Tags\Contracts\RemoveTagBusinessInterface;
use App\Features\General\Tags\Contracts\ShowTagBusinessInterface;
use App\Features\General\Tags\Contracts\TagsRepositoryInterface;
use App\Features\General\Tags\Contracts\UpdateStatusTagBusinessInterface;
use App\Features\General\Tags\Contracts\UpdateTagBusinessInterface;
use App\Features\General\Tags\Repositories\TagsRepository;

class TagsProviders extends ServiceProvider
{
    public array $bindings = [
        TagsRepositoryInterface::class => TagsRepository::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->bind(
            FindAllTagsBusinessInterface::class,
            FindAllTagsBusiness::class
        );

        $this->bind(
            ShowTagBusinessInterface::class,
            ShowTagBusiness::class
        );

        $this->bind(
            CreateTagBusinessInterface::class,
            CreateTagBusiness::class
        );

        $this->bind(
            UpdateTagBusinessInterface::class,
            UpdateTagBusiness::class
        );

        $this->bind(
            UpdateStatusTagBusinessInterface::class,
            UpdateStatusTagBusiness::class
        );

        $this->bind(
            RemoveTagBusinessInterface::class,
            RemoveTagBusiness::class
        );
    }
}
