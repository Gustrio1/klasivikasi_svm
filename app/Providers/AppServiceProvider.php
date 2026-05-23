<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
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
        // Set locale tanggal ke Bahasa Indonesia
        Carbon::setLocale('id');

        // Gunakan Tailwind CSS untuk styling pagination
        Paginator::useTailwind();
    }
}
