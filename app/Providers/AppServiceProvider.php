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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set locale from session if it exists
        if (session()->has('locale')) {
            $locale = session('locale');
            if (in_array($locale, ['ru', 'kk'])) {
                app()->setLocale($locale);
            }
        }
    }
}
