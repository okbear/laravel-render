<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(UrlGenerator $url): void
    {
        // Render は TLS を終端するので、本番環境では HTTPS を強制する
        if (config('app.env') === 'production') {
            $url->forceScheme('https');
        }
    }
}
