<?php

use Illuminate\Support\Facades\Route;
use Shops\Application\Http\Controllers\ShopController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('shops', ShopController::class);
});
