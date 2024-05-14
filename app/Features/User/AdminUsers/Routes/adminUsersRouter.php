<?php

use App\Features\User\AdminUsers\Controllers\AdminUsersController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminUsersController::class, 'index']);

Route::get('/id/{id}', [AdminUsersController::class, 'showById'])->middleware(ValidateUuid4::class);

Route::post('/', [AdminUsersController::class, 'insert']);

Route::put('/id/{id}', [AdminUsersController::class, 'update'])->middleware(ValidateUuid4::class);
Route::put('/status/{id}', [AdminUsersController::class, 'updateStatus'])->middleware(ValidateUuid4::class);
