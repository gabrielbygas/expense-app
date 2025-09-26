<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ExpensesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(base_path('modules/expenses/database/migrations'));
        $this->loadRoutesFrom(base_path('modules/expenses/routes/api.php'));
        $this->loadViewsFrom(base_path('modules/expenses/resources/views'), 'expenses');
    }

    public function register(): void
    {
        // Tu peux binder des services ici si besoin
    }
}