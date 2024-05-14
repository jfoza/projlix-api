<?php

use App\Features\User\TeamUsers\Controllers\TeamUsersController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [TeamUsersController::class, 'index']);

Route::get('/id/{id}', [TeamUsersController::class, 'showById'])->middleware(ValidateUuid4::class);

Route::post('/', [TeamUsersController::class, 'insert']);

Route::put('/id/{id}', [TeamUsersController::class, 'update'])->middleware(ValidateUuid4::class);
Route::put('/status/{id}', [TeamUsersController::class, 'updateStatus'])->middleware(ValidateUuid4::class);
