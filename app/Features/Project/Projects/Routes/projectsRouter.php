<?php

use App\Features\Project\Projects\Controllers\ProjectsController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectsController::class, 'index']);
Route::get('/{id}', [ProjectsController::class, 'show'])->middleware(ValidateUuid4::class);
Route::post('/', [ProjectsController::class, 'insert']);
Route::put('/{id}', [ProjectsController::class, 'update'])->middleware(ValidateUuid4::class);
Route::delete('/{id}', [ProjectsController::class, 'delete'])->middleware(ValidateUuid4::class);
