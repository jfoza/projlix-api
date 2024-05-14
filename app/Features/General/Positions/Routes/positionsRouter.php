<?php

use App\Features\General\Positions\Controllers\PositionsController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [PositionsController::class, 'index']);
Route::get('/{id}', [PositionsController::class, 'show'])->middleware(ValidateUuid4::class);
Route::post('/', [PositionsController::class, 'insert']);
Route::put('/{id}', [PositionsController::class, 'update'])->middleware(ValidateUuid4::class);
Route::delete('/{id}', [PositionsController::class, 'delete'])->middleware(ValidateUuid4::class);
