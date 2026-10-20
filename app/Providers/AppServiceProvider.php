<?php

namespace App\Providers;

use App\Services\SubscriptionService;
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

