<?php

use App\Features\Project\Cards\Controllers\CardsController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [CardsController::class, 'index']);
Route::get('/{id}', [CardsController::class, 'show'])->middleware(ValidateUuid4::class);
Route::post('/', [CardsController::class, 'insert']);
Route::put('/{id}', [CardsController::class, 'update'])->middleware(ValidateUuid4::class);
Route::delete('/{id}', [CardsController::class, 'delete'])->middleware(ValidateUuid4::class);
