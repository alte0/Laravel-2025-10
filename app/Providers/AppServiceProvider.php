<?php

namespace App\Providers;

use App\Domain\TaskSubDomain\Repositories\TaskRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\Task;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TaskRepositoryInterface::class,
            Task::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
