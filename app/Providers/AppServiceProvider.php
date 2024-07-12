<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use App\Models\Cms;

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
        Paginator::useBootstrap();

        Schema::defaultStringLength(255);
       
        //\Illuminate\Support\Facades\URL::forceScheme('https');


        \View::composer('frontend.layouts.navbar', function($view)
        {
            $cmsObject = new Cms();
            $cmsPages  = $cmsObject->getPages();
            $view->with('cmsPages', $cmsPages); // you can pass array here aswell
        });


        \View::composer('frontend.layouts.footer', function($view)
        {
            $cmsObject = new Cms();
            $cmsPages  = $cmsObject->getPages();
            $view->with('cmsPages', $cmsPages); // you can pass array here aswell
        });
    }
}
