<?php

namespace App\Providers;

use App\Models\NavItem;
use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        // The live MySQL caps an index at 1000 bytes (MyISAM). A 255-character
        // utf8mb4 string is 1020 bytes, so indexed strings stay at 191.
        Schema::defaultStringLength(191);

        // The menu is admin-managed, so the layout resolves it itself rather
        // than every controller having to remember to pass it down.
        View::composer('layouts.site', function ($view) {
            $view->with('navItems', NavItem::forSite());
            $view->with('siteTheme', Setting::siteTheme());
        });

        RateLimiter::for('quotes', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
