<?php

namespace App\Providers;
use App\View\Components\Composers\DropdownComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades;
use Illuminate\View\View;


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
        //
        Facades\View::composer('user::components.dropdown', \App\View\Composers\DropdownComposer::class);
    }
}
