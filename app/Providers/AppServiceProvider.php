<?php

namespace App\Providers;

use App\Services\SettingsService;
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
        $appUrl = (string) config('app.url', '');
        $forceHttps = filter_var(env('FORCE_HTTPS', false), FILTER_VALIDATE_BOOLEAN)
            || str_starts_with($appUrl, 'https://');

        if ($forceHttps) {
            URL::forceScheme('https');
        }

        View::composer(['layouts.app', 'layouts.*', 'admin.*', 'tenant.*', 'components.*'], function ($view) {
            try {
                $modules = SettingsService::get()['modules'] ?? [];
            } catch (\Throwable) {
                $modules = [];
            }
            $view->with('planModules', $modules);

            if (! auth()->check() || (auth()->user()->is_superadmin ?? false)) {
                $view->with('isProUser', true);
                $view->with('subscriptionSummary', ['is_pro' => true, 'plan' => 'pro']);

                return;
            }

            try {
                $summary = ['is_pro' => false, 'plan' => 'free'];
                if (function_exists('tenancy') && tenancy()->initialized) {
                    $summary = app(SubscriptionService::class)->subscriptionSummary();
                }
                $view->with('subscriptionSummary', $summary);
                $view->with('isProUser', (bool) ($summary['is_pro'] ?? false));
            } catch (\Throwable) {
                $view->with('subscriptionSummary', ['is_pro' => false, 'plan' => 'free']);
                $view->with('isProUser', false);
            }
        });
    }
}
