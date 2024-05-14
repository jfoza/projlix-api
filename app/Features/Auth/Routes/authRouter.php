<?php

use App\Features\Auth\Controllers\AuthController;
use App\Http\Middleware\AuthGuard;
use Illuminate\Support\Facades\Route;

Route::get('/logout', [AuthController::class, 'destroy'])->middleware(AuthGuard::class);

Route::post('/login', [AuthController::class, 'create']);
Route::post('/login/google', [AuthController::class, 'createWithGoogle']);
