<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Object Service and Repository
        $this->app->bind(
            \App\Services\Object\ObjectService::class,
            \App\Services\Object\ObjectServiceImplement::class
        );
        
        $this->app->bind(
            \App\Repositories\Object\ObjectRepository::class,
            \App\Repositories\Object\ObjectRepositoryImplement::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
