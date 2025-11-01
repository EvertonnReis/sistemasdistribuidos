<?php

namespace App\Providers;

use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\CourseRepository;
use App\Repositories\LessonRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Repository Interfaces
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
