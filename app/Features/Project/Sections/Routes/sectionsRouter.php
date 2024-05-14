<?php

use App\Features\Project\Sections\Controllers\SectionsController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [SectionsController::class, 'index']);
Route::get('/{id}', [SectionsController::class, 'show'])->middleware(ValidateUuid4::class);
Route::post('/', [SectionsController::class, 'insert']);
Route::put('/{id}', [SectionsController::class, 'update'])->middleware(ValidateUuid4::class);
Route::delete('/{id}', [SectionsController::class, 'delete'])->middleware(ValidateUuid4::class);
