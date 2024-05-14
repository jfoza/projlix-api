<?php

use App\Features\General\Icons\Controllers\IconsController;
use App\Http\Middleware\ValidateUuid4;
use Illuminate\Support\Facades\Route;

Route::get('/', [IconsController::class, 'index']);
Route::get('/{id}', [IconsController::class, 'show'])->middleware(ValidateUuid4::class);
