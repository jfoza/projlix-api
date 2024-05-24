<?php

return [
    /**
     * Laravel
     */
    \App\Providers\AppServiceProvider::class,
    \Tymon\JWTAuth\Providers\LaravelServiceProvider::class,

    /**
     * Features
     */
    \App\Features\Auth\Providers\AuthProvider::class,
    \App\Features\User\Rules\Providers\RulesProviders::class,
    \App\Features\User\Users\Providers\UsersProviders::class,
    \App\Features\User\Profiles\Providers\ProfilesProviders::class,
    \App\Features\User\AdminUsers\Providers\AdminUsersProviders::class,
    \App\Features\User\Profiles\Providers\ProfilesProviders::class,
    \App\Features\General\Positions\Providers\PositionsProviders::class,
    \App\Features\Project\Projects\Providers\ProjectsProviders::class,
    \App\Features\User\TeamUsers\Providers\TeamUsersProviders::class,
    \App\Features\General\Notes\Providers\NotesProvider::class,
    \App\Features\General\Colors\Providers\ColorsProviders::class,
    \App\Features\General\Icons\Providers\IconsProviders::class,
    \App\Features\Project\Sections\Providers\SectionsProviders::class,
    \App\Features\General\Tags\Providers\TagsProviders::class,
    \App\Features\Project\Cards\Providers\CardsProviders::class,
];
