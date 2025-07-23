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
        // Bind repository interfaces to implementations
        $this->app->bind(
            \App\Domain\Loan\Contracts\LoanRepositoryInterface::class,
            \App\Infrastructure\Repositories\EloquentLoanRepository::class
        );

        $this->app->bind(
            \App\Domain\Loan\Contracts\AmortizationScheduleRepositoryInterface::class,
            \App\Infrastructure\Repositories\EloquentAmortizationScheduleRepository::class
        );

        // Register domain services
        $this->app->singleton(\App\Domain\Loan\Services\MonthlyPaymentCalculator::class);
        $this->app->singleton(\App\Domain\Loan\Services\EffectiveRateCalculator::class);
        $this->app->singleton(\App\Domain\Loan\Services\ScheduleGenerator::class);
        
        // Register payment strategies
        $this->app->singleton(\App\Domain\Loan\Services\StandardPaymentStrategy::class);
        $this->app->singleton(\App\Domain\Loan\Services\ExtraPaymentStrategy::class);
        
        // Keep backward compatibility for existing LoanAmortizationService
        $this->app->singleton(\App\Domain\Loan\Services\LoanAmortizationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
