<?php

use App\Features\General\Colors\Controllers\ColorsController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [ColorsController::class, 'index']);
Route::get('/{id}', [ColorsController::class, 'show'])->middleware(ValidateUuid4::class);
