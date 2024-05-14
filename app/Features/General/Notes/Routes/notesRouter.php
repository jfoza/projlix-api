<?php

use App\Features\General\Notes\Controllers\NotesController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [NotesController::class, 'index']);
Route::get('/{id}', [NotesController::class, 'show'])->middleware(ValidateUuid4::class);
Route::post('/', [NotesController::class, 'insert']);
Route::put('/{id}', [NotesController::class, 'update'])->middleware(ValidateUuid4::class);
Route::delete('/{id}', [NotesController::class, 'delete'])->middleware(ValidateUuid4::class);
