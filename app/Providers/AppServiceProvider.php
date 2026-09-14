<?php

namespace App\Providers;

use App\Models\NavItem;
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
        // The menu is admin-managed, so the layout resolves it itself rather
        // than every controller having to remember to pass it down.
        View::composer('layouts.site', function ($view) {
            $view->with('navItems', NavItem::forSite());
        });
    }
}
