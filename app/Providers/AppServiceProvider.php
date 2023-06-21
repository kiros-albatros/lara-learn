<?php

namespace App\Providers;

use App\Hashing\KohanaHasher;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Hash::extend('kohana', static function () {
            return new KohanaHasher(config('hashing.kohana'));
        });
    }
}
