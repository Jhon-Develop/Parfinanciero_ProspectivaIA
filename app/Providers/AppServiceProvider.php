<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ExpenseRepositoryInterface;
use App\Repositories\GoalRepositoryInterface;
use App\Repositories\Fake\FakeExpenseRepository;
use App\Repositories\Fake\FakeGoalRepository;
use App\Repositories\Api\ApiExpenseRepository;
use App\Repositories\Api\ApiGoalRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local', 'testing')) {
            // Usa los repositorios Fake en entornos de desarrollo y pruebas
            $this->app->bind(ExpenseRepositoryInterface::class, FakeExpenseRepository::class);
            $this->app->bind(GoalRepositoryInterface::class, FakeGoalRepository::class);
        } else {
            // Usa los repositorios de la API en entornos de producción
            // $this->app->bind(ExpenseRepositoryInterface::class, ApiExpenseRepository::class);
            // $this->app->bind(GoalRepositoryInterface::class, ApiGoalRepository::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
