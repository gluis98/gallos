<?php

namespace App\Policies;

use App\Models\Gallina;
use App\Models\Subscription;
use App\Models\User;
use App\Services\SettingsService;
use App\Services\UserGalponService;

class GallinaPolicy
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
        $limit = (int) (SettingsService::get()['plans']['free_gallinas_limit'] ?? 20);
        $count = Gallina::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->count();

        return $count < $limit;
    }

    public function update(User $user, Gallina $gallina): bool
    {
        $tenantId = app(UserGalponService::class)->resolveActiveTenantId($user);

        return $user->is_superadmin || (string) $gallina->tenant_id === (string) $tenantId;
    }

    public function delete(User $user, Gallina $gallina): bool
    {
        return $this->update($user, $gallina);
    }
}
