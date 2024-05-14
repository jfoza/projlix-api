<?php

use App\Features\General\Tags\Controllers\TagsController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [TagsController::class, 'index']);
Route::get('/{id}', [TagsController::class, 'show'])->middleware(ValidateUuid4::class);

Route::post('/', [TagsController::class, 'insert']);

Route::put('/id/{id}', [TagsController::class, 'update'])->middleware(ValidateUuid4::class);
Route::put('/status/{id}', [TagsController::class, 'updateStatus'])->middleware(ValidateUuid4::class);

Route::delete('/id/{id}', [TagsController::class, 'delete'])->middleware(ValidateUuid4::class);


