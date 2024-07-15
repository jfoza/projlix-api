<?php

use App\Features\Project\Projects\Controllers\ProjectsController;
use App\Features\Project\Projects\Controllers\ProjectsUpdateController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectsController::class, 'index']);
Route::get('/id/{id}', [ProjectsController::class, 'show'])->middleware(ValidateUuid4::class);

Route::post('/', [ProjectsController::class, 'insert']);

Route::put('/info/id/{id}', [ProjectsUpdateController::class, 'updateInfo'])->middleware(ValidateUuid4::class);
Route::put('/icon/id/{id}', [ProjectsUpdateController::class, 'updateIcon'])->middleware(ValidateUuid4::class);
Route::put('/tag/id/{id}', [ProjectsUpdateController::class, 'addTag'])->middleware(ValidateUuid4::class);

Route::delete('/tag/id/{id}', [ProjectsUpdateController::class, 'removeTag'])->middleware(ValidateUuid4::class);
Route::delete('/id/{id}', [ProjectsController::class, 'delete'])->middleware(ValidateUuid4::class);
