<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Правильное опраделение политик доступа при передаче DTO вместо моделей
        Gate::guessPolicyNamesUsing(function ($className) {
            $regex = '~^(?<domain>[A-Za-z\d]+)\\\\'
                // Обеспечиваем поддержку и моделей тоже, т.к. этот метод теперь заменяет старый механизм полностью
                . '((Contracts\\\\DataTransferObjects)|Domain\\\\Models)\\\\'
                . '(?<model>[A-Za-z\d]+?)(Dto)?$~';
            if (preg_match($regex, $className, $matches)) {
                return $matches['domain'] . '\\Application\\Policies\\' . $matches['model'] . 'Policy';
            }
            return null;
        });
    }

    public function register()
    {
        /**
         * Регистрируем модули, которые заданы в .env
         *
         * Для каждого из них должен существовать свой ServiceProvider, даже не делаем доп.проверок
         */
        foreach (config('app.modules', []) as $module) {
            $this->app->register("\\$module\\Providers\\${module}ServiceProvider");
        }
    }
}
