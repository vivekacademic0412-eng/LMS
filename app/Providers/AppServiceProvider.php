<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

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
        $appUrl = (string) config('app.url', '');

        if (! $this->app->runningInConsole()) {
            $request = request();
            $rootUrl = $request->getSchemeAndHttpHost();

            if ($rootUrl !== '') {
                URL::forceRootUrl($rootUrl);
            }

            if ($request->isSecure() || Str::contains((string) $request->header('x-forwarded-proto'), 'https')) {
                URL::forceScheme('https');

                return;
            }
        }

        if ($appUrl !== '' && (app()->environment('production') || Str::startsWith($appUrl, 'https://'))) {
            URL::forceScheme('https');
        }
    }
}
