<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\CourseRepository;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\LessonRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registrar o binding do CourseRepository
        $this->app->bind(
            CourseRepositoryInterface::class,
            CourseRepository::class
        );

        // Registrar o binding do LessonRepository
        $this->app->bind(
            LessonRepositoryInterface::class,
            LessonRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
