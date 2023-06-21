<?php

use Illuminate\Support\Facades\Route;
use Accounts\Application\Http\Controllers\LoginController;
use Accounts\Application\Http\Controllers\UserController;
use App\Http\Controllers\Api\AdminController;

Route::middleware('web')->group(function () {
    Route::get('/login/', [LoginController::class, 'create'])->name('login');
    Route::post('/login/', [LoginController::class, 'store']);
    Route::post('/logout/', [LoginController::class, 'destroy'])->middleware('auth');

    Route::middleware('auth')->group(function () {
        Route::get('/', fn() => inertia('Dashboard'))->name('dashboard');

        // Пользователи
        Route::resource('users', UserController::class)->where(['user' => '\d+']);
    });
});
