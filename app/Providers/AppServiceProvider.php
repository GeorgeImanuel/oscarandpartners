<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Repositories\Payment\PaymentRepository;

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

        // Register ObjectOccupation Service and Repository
        $this->app->bind(
            \App\Services\ObjectOccupation\ObjectOccupationService::class,
            \App\Services\ObjectOccupation\ObjectOccupationServiceImplement::class
        );
        
        $this->app->bind(
            \App\Repositories\ObjectOccupation\ObjectOccupationRepository::class,
            \App\Repositories\ObjectOccupation\ObjectOccupationRepositoryImplement::class
        );

        // Register Interest Service and Repository
        $this->app->bind(
            \App\Services\Interest\InterestService::class,
            \App\Services\Interest\InterestServiceImplement::class
        );
        
        $this->app->bind(
            \App\Repositories\Interest\InterestRepository::class,
            \App\Repositories\Interest\InterestRepositoryImplement::class
        );

        // Register Claim Service and Repository
        $this->app->bind(
            \App\Services\Claim\ClaimService::class,
            \App\Services\Claim\ClaimServiceImplement::class
        );
        
        $this->app->bind(
            \App\Repositories\Claim\ClaimRepository::class,
            \App\Repositories\Claim\ClaimRepositoryImplement::class
        );

        // Register Currency Service and Repository
        $this->app->bind(
            \App\Services\Currency\CurrencyService::class,
            \App\Services\Currency\CurrencyServiceImplement::class
        );
        
        $this->app->bind(
            \App\Repositories\Currency\CurrencyRepository::class,
            \App\Repositories\Currency\CurrencyRepositoryImplement::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(PaymentRepository $paymentRepository)
    {
        View::composer('*', function ($view) use ($paymentRepository) {
            $notifData = $paymentRepository->getOverduePayments(); 
            
            $view->with('overdueNotifications', $notifData);
        });
    }
}
