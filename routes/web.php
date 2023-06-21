<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\DevController;

Route::middleware('auth')->group(function () {
    Route::get('/', fn() => inertia('Dashboard'))->name('dashboard');
});

if (env('APP_DEBUG', false)) {
    // Роуты для быстрых development-тестов
    Route::get('/dev/{action?}', [DevController::class, 'index']);
}
