<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
    public function boot(UrlGenerator $url): void
    {
        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }

        // Default Password
        Password::defaults(function () {
            $rules = Password::min(5);

            // return App::isProduction()
            //     ? $rules->letters()->mixedCase()->numbers()->symbols()
            //     : $rules;

            return $rules->letters()->mixedCase()->numbers()->symbols();
        });
    }
}
