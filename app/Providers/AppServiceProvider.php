<?php

namespace App\Providers;

use Illuminate\Support\Collection;
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
        // Force load classes that are often serialized to avoid "incomplete object" errors in Octane
        class_exists(Collection::class);
        class_exists(\stdClass::class);
    }
}
