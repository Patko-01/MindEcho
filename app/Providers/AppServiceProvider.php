<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5 pagination views so the default links() output matches the project's Bootstrap CSS
        if (method_exists(Paginator::class, 'useBootstrapFive')) {
            Paginator::useBootstrapFive();
        } else {
            // Fallback to the generic useBootstrap if the specific method is unavailable
            Paginator::useBootstrap();
        }
    }
}
