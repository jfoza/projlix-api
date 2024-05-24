<?php

use App\Features\User\Profiles\Controllers\ProfilesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProfilesController::class, 'index']);
