<?php

namespace App\Providers;

use Illuminate\Http\Request;
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
        $appUrl = trim((string) config('app.url', ''));

        if ($this->app->runningInConsole()) {
            if ($appUrl !== '') {
                URL::forceRootUrl($appUrl);
            }

            if ($appUrl !== '' && (app()->environment('production') || Str::startsWith($appUrl, 'https://'))) {
                URL::forceScheme('https');
            }

            return;
        }

        $request = request();
        $forwardedProto = $this->firstForwardedHeaderValue($request, 'x-forwarded-proto');
        $forwardedHost = $this->firstForwardedHeaderValue($request, 'x-forwarded-host');
        $forwardedPort = $this->firstForwardedHeaderValue($request, 'x-forwarded-port');
        $scheme = ($request->isSecure() || $forwardedProto === 'https') ? 'https' : $request->getScheme();
        $rootUrl = $this->resolveRequestRootUrl($request, $scheme, $forwardedHost, $forwardedPort);

        if ($rootUrl === '' && $appUrl !== '') {
            $rootUrl = $appUrl;
        }

        if ($rootUrl !== '') {
            URL::forceRootUrl($rootUrl);
        }

        if ($scheme === 'https' || ($appUrl !== '' && (app()->environment('production') || Str::startsWith($appUrl, 'https://')))) {
            URL::forceScheme('https');
        }
    }

    private function firstForwardedHeaderValue(Request $request, string $header): string
    {
        return trim(Str::before((string) $request->header($header, ''), ','));
    }

    private function resolveRequestRootUrl(Request $request, string $scheme, string $forwardedHost, string $forwardedPort): string
    {
        $host = $forwardedHost !== '' ? $forwardedHost : $request->getHost();

        if ($host === '') {
            return '';
        }

        if (Str::contains($host, '://')) {
            return $host;
        }

        if (str_contains($host, ':') && ! str_starts_with($host, '[')) {
            return $scheme.'://'.$host;
        }

        $port = $this->resolveRequestPort($request, $forwardedPort);

        if ($port === '' || $this->isDefaultPublicPort($scheme, $port)) {
            return $scheme.'://'.$host;
        }

        return $scheme.'://'.$host.':'.$port;
    }

    private function resolveRequestPort(Request $request, string $forwardedPort): string
    {
        if ($forwardedPort !== '' && ctype_digit($forwardedPort)) {
            return $forwardedPort;
        }

        $requestPort = (string) $request->getPort();

        return ctype_digit($requestPort) ? $requestPort : '';
    }

    private function isDefaultPublicPort(string $scheme, string $port): bool
    {
        if ($port === '') {
            return true;
        }

        if ($scheme === 'https') {
            return in_array($port, ['80', '443'], true);
        }

        return $scheme === 'http' && $port === '80';
    }
}
