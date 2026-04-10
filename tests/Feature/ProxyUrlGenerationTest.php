<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProxyUrlGenerationTest extends TestCase
{
    public function test_forwarded_host_is_used_for_generated_urls_without_appending_internal_port_80(): void
    {
        Route::get('/_proxy-url-test', fn () => route('dashboard'))->name('proxy-url-test');

        $response = $this->withServerVariables([
            'HTTP_HOST' => 'internal.railway.local',
            'HTTP_X_FORWARDED_HOST' => 'lms-production-ed97.up.railway.app',
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'SERVER_PORT' => '80',
        ])->get('/_proxy-url-test');

        $response->assertOk();
        $response->assertSee('https://lms-production-ed97.up.railway.app/dashboard', false);
        $response->assertDontSee(':80/dashboard', false);
    }

    public function test_forwarded_https_url_ignores_forwarded_port_80(): void
    {
        Route::get('/_proxy-url-test-port', fn () => route('dashboard'))->name('proxy-url-test-port');

        $response = $this->withServerVariables([
            'HTTP_HOST' => 'internal.railway.local',
            'HTTP_X_FORWARDED_HOST' => 'lms-production-ed97.up.railway.app',
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'HTTP_X_FORWARDED_PORT' => '80',
            'SERVER_PORT' => '80',
        ])->get('/_proxy-url-test-port');

        $response->assertOk();
        $response->assertSee('https://lms-production-ed97.up.railway.app/dashboard', false);
        $response->assertDontSee(':80/dashboard', false);
    }
}
