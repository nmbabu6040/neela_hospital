<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('siteSettings', SiteSetting::pluck('value', 'key')->all());
        });
    }
}
