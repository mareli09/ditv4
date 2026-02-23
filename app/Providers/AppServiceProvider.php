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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $rawContents = \App\Models\WebsiteContent::all();
            $contents = $rawContents->groupBy('key')->map(function ($item) {
                return $item->first()->value;
            })->toArray();

            $view->with('contents', $contents);
        });
    }
}
