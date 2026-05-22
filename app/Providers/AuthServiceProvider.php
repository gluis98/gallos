<?php

namespace App\Providers;

use App\Models\Gallo;
use App\Models\Gallina;
use App\Policies\GalloPolicy;
use App\Policies\GallinaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Gallo::class => GalloPolicy::class,
        Gallina::class => GallinaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('superadmin', fn ($user) => (bool) $user->is_superadmin);
    }
}
