<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Expenses\Events\ExpenseCreated;
use Modules\Expenses\Listeners\SendExpenseNotification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;

class ExpensesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(base_path('modules/Expenses/Database/Migrations'));
        //$this->loadRoutesFrom(base_path('modules/Expenses/Routes/api.php'));
        Route::middleware('api')->prefix('api')->group(function () {
            $this->loadRoutesFrom(base_path('modules/Expenses/Routes/api.php'));
        });
        $this->loadViewsFrom(base_path('modules/Expenses/Resources/views'), 'expenses');

        // Events -> listeners (simple binding)
        $events = $this->app['events'];
        $events->listen(ExpenseCreated::class, [SendExpenseNotification::class, 'handle']);

        //Factories
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Modules\\Expenses\\Database\\Factories\\' . class_basename($modelName) . 'Factory';
        });
    }

    public function register(): void
    {
        // Tu peux binder des services ici si besoin
    }
}