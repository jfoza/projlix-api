<?php

namespace App\Features\General\Notes\Providers;

use App\Features\Base\Providers\ServiceProvider;
use App\Features\General\Notes\Business\CreateNoteBusiness;
use App\Features\General\Notes\Business\FindAllNotesBusiness;
use App\Features\General\Notes\Business\RemoveNoteBusiness;
use App\Features\General\Notes\Business\ShowNoteBusiness;
use App\Features\General\Notes\Business\UpdateNoteBusiness;
use App\Features\General\Notes\Contracts\CreateNoteBusinessInterface;
use App\Features\General\Notes\Contracts\FindAllNotesBusinessInterface;
use App\Features\General\Notes\Contracts\NotesRepositoryInterface;
use App\Features\General\Notes\Contracts\RemoveNoteBusinessInterface;
use App\Features\General\Notes\Contracts\ShowNoteBusinessInterface;
use App\Features\General\Notes\Contracts\UpdateNoteBusinessInterface;
use App\Features\General\Notes\Repositories\NotesRepository;

class NotesProvider extends ServiceProvider
{
    public array $bindings = [
        NotesRepositoryInterface::class => NotesRepository::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->bind(
            FindAllNotesBusinessInterface::class,
            FindAllNotesBusiness::class
        );

        $this->bind(
            ShowNoteBusinessInterface::class,
            ShowNoteBusiness::class
        );

        $this->bind(
            CreateNoteBusinessInterface::class,
            CreateNoteBusiness::class
        );

        $this->bind(
            UpdateNoteBusinessInterface::class,
            UpdateNoteBusiness::class
        );

        $this->bind(
            RemoveNoteBusinessInterface::class,
            RemoveNoteBusiness::class
        );
    }
}
