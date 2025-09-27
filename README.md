# Expense Module (Modules/Expenses)

## Purpose
A self-contained Laravel module to manage expenses (CRUD + filters) for Laravel 12 compatibility.

## Requirements
- PHP 8.1+  
- Composer  
- MySQL/Postgres/SQLite database  
- Laravel 12.x

## Installation
**1. Clone the repository:**
```bash
   git clone https://github.com/gabrielbygas/expense-app.git
   cd expense-app
   composer install
```
**2. Configure environment:**
```bash
cp .env.example .env
php artisan key:generate
```
**3. Configure the Modules:**
a. Register the namespace
```json
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/",
            "Modules\\Expenses\\": "modules/Expenses/",
            "Modules\\Expenses\\Database\\Factories\\": "modules/Expenses/Database/Factories/",
            "Modules\\Expenses\\Database\\Seeders\\": "modules/Expenses/Database/Seeders/"
        }
    },
```
Then run
```bash
composer dump-autoload
```
b. Create the Module Service Provider :**`modules/Expenses/Providers/ExpensesServiceProvider.php`** 
```php
<?php

namespace Modules\Expenses\Providers;

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
        $this->app->register(RouteServiceProvider::class);
    }
}
```


c. Create the Module Route Provider **`modules/Expenses/Providers/RouteServiceProvider.php`**
```php
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
```
d. Register the module service provider in **`bootstrap/providers.php`**:
```php
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\ExpensesServiceProvider::class,
    Modules\Expenses\Providers\ExpensesServiceProvider::class,
];
```
e. Clear Cache and Reload
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```
Then run
```bash
composer dump-autoload
```
## Project Structure
```bash
modules/Expenses/
├─ Database/
│  ├─ Migrations/               # Expense table migration (UUID + enum category)
│  ├─ Seeders/                  # Seeder with valid category values
│  └─ Factories/                # Factory with Faker for sample data
├─ Enums/                       # Category enum
├─ Events/                      # ExpenseCreated events
├─ Http/
│  ├─ Controllers/              # ExpenseController (API CRUD)
│  ├─ Requests/                 # Store/Update FormRequests (validation rules)
│  ├─ Resources/                # ExpenseResource
├─ Listeners/                   # SendExpenseNotification listener
├─ Models/                      # Expense model (UUID primary key, casts)
├─ Providers/                   # ModuleServiceProvider (routes, migrations, events)
├─ Repositories/                # Interface + Eloquent implementation
├─ Resources                    # API routes (CRUD endpoints)
├─ Routes/                      # API routes (CRUD endpoints)
├─ Services/                    # ExpenseService (business logic + events)
└─ Tests/                       # Feature test examples

```

## Run Migrations and Seeders
```bash
php artisan migrate:fresh
php artisan migrate --seed
```
## API Endpoints
```bash
| Method | Endpoint                  | Description                      |
|--------|---------------------------|----------------------------------|
| GET    | /api/expenses             | List all expenses                |
| POST   | /api/expenses             | Create an expense                |
| GET    | /api/expenses/{id}        | Show an expense                  |
| PUT    | /api/expenses/{id}        | Update an expense                |
| DELETE | /api/expenses/{id}        | Delete an expense                |
| GET    | /api/expenses/filter      | Filter by category/date range    |
```
## Testing
Feature test : `Modules/Expenses/Tests/Feature/ExpenseTest.php`.
```bash
php artisan test
```
## Design Decisions & Common Issues
- `UUID` used for primary key (id) to meet the brief.
- No Authentication: As per requirements, authentication is not implemented.
- Ensure migrations are in `Modules/Expenses/Database/Migrations/` and load it to `ExpensesServiceProvider`.
- `Category` implemented as enum with fixed values: `food,transport,utilities,entertainment,other`
- Ensure Seeders is added to `database/seeders/DatabaseSeeder.php`
- Ensure Factory is loaded to `Modules/Expenses/Providers/ExpensesServiceProvider`
- Service Layer is mandatory (per brief) → contains business rules and event dispatching.
- `Events/Listeners` added → `ExpenseCreated` triggers `SendExpenseNotification`.
- `ModuleServiceProvider` updated for Laravel 12 → loads routes, migrations, and registers bindings/events.
- IDE fixes: PSR-4 autoload
## Estimated Time Spent
```txt
Approx. 6–8 hours (implementation, debugging provider, fixing Seeder/Factory, writing tests, README).
```
## License
This project is open-sourced under the MIT license.
## Author
**Gabriel KALALA**