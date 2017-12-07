<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::if ('viewmanage_backend', function () {
            return (Gate::allows('manage-backend') || Gate::allows('view-backend'));
        });

        Blade::if ('show_contact', function () {
            return (!Auth::user() || !(Gate::allows('manage-backend') || Gate::allows('view-backend')));
        });

        Blade::if ('manage_backend', function () {
            return (Gate::allows('manage-backend'));
        });

        Blade::if ('prod', function () {
            return app()->environment('production');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
