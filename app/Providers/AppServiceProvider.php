<?php

namespace App\Providers;

use App\Services\SubscriptionService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Forzar HTTPS en URLs (route/url/asset) cuando APP_URL es https
        // o cuando FORCE_HTTPS=true. Evita formularios con action http:// detrás de proxy SSL.
        $appUrl = (string) config('app.url', '');
        $forceHttps = filter_var(env('FORCE_HTTPS', false), FILTER_VALIDATE_BOOLEAN)
            || str_starts_with($appUrl, 'https://');

        if ($forceHttps) {
            URL::forceScheme('https');
        }

        View::composer(['layouts.app', 'admin.*'], function ($view) {
            if (! auth()->check() || auth()->user()->is_superadmin) {
                return;
            }

            try {
                if (tenancy()->initialized) {
                    $view->with('subscriptionSummary', app(SubscriptionService::class)->subscriptionSummary());
                }
            } catch (\Throwable) {
                // tenancy may not be initialized on some routes
            }
        });
    }
}

