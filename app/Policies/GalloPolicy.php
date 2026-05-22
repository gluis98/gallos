<?php

namespace App\Policies;

use App\Models\Gallo;
use App\Models\Subscription;
use App\Models\User;
use App\Services\SettingsService;
use App\Services\UserGalponService;

class GalloPolicy
{
    public function create(User $user): bool
    {
        if ($user->is_superadmin) {
            return true;
        }

        if (! $user->tenant_id) {
            return false;
        }

        $sub = Subscription::query()
            ->where('status', 'active')
            ->latest('id')
            ->first();

        $plan = $sub?->plan ?? 'free';

        if ($plan !== 'free') {
            return true;
        }

        $tenantId = app(UserGalponService::class)->resolveActiveTenantId($user);
        $limit = (int) (SettingsService::get()['plans']['free_gallos_limit'] ?? 20);
        $count = Gallo::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->count();

        return $count < $limit;
    }

    public function update(User $user, Gallo $gallo): bool
    {
        $tenantId = app(UserGalponService::class)->resolveActiveTenantId($user);

        return $user->is_superadmin || (string) $gallo->tenant_id === (string) $tenantId;
    }

    public function delete(User $user, Gallo $gallo): bool
    {
        return $this->update($user, $gallo);
    }
}
