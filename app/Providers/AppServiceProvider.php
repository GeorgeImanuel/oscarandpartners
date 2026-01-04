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

        // Register ObjectType Service and Repository
        $this->app->bind(
            \App\Services\ObjectType\ObjectTypeService::class,
            \App\Services\ObjectType\ObjectTypeServiceImplement::class
        );
        
        $this->app->bind(
            \App\Repositories\ObjectType\ObjectTypeRepository::class,
            \App\Repositories\ObjectType\ObjectTypeRepositoryImplement::class
        );

        // Register Part Service and Repository
        $this->app->bind(
            \App\Services\Part\PartService::class,
            \App\Services\Part\PartServiceImplement::class
        );
        
        $this->app->bind(
            \App\Repositories\Part\PartRepository::class,
            \App\Repositories\Part\PartRepositoryImplement::class
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
