<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //URL::forceScheme('https');

        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $view->with('navApiSettings', DB::table('settings')
                    ->select('id', 'name', 'selected')
                    ->where('user_id', auth()->id())
                    ->get());
            }
        });
    }

    public function register(): void
    {
        //
    }
}
