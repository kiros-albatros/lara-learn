<?php

namespace Accounts\Providers;

use App\Helpers\DomainServiceProvider;

class AccountsServiceProvider extends DomainServiceProvider
{
    protected array $policies  =  [
        'Accounts\Domain\Models\User' => 'Accounts\Application\Policies\UserPolicy',
    ];

    public function register()
    {
    }


    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'auth');

        $this->registerPolicies();
    }
}
