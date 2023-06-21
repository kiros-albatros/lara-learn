<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

abstract class DomainServiceProvider extends ServiceProvider
{
    protected array $policies  =  [];

    protected function registerPolicies()
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
