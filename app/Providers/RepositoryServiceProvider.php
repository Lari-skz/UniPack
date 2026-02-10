<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Import all interfaces
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\EventRepositoryInterface;

// Import all implementations
use App\Repositories\CategoryRepository;
use App\Repositories\TaskRepository;
use App\Repositories\EventRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repository bindings
     *
     * This tells Laravel: "When someone asks for CategoryRepositoryInterface,
     * give them a CategoryRepository instance"
     */
    public function register(): void
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );

        $this->app->bind(
            EventRepositoryInterface::class,
            EventRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
