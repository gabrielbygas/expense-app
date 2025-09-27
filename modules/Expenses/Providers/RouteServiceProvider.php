<?php

namespace Modules\Expenses\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Expenses';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        // with nwidart/laravel-modules
        // Route::middleware('web')->group(module_path($this->name, '/Routes/web.php'));
        // without
        Route::middleware('web')->group(base_path('modules/Expenses/Routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        // with nwidart/laravel-modules
        // Route::middleware('api')->prefix('api')->name('api.')->group(module_path($this->name, '/Routes/api.php'));
        // without
        Route::middleware('api')->prefix('api')->name('api.')->group(base_path('modules/Expenses/Routes/web.php'));
    }
}