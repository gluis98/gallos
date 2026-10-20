<?php

namespace App\Policies;

use App\Models\Gallina;
use App\Models\User;
use App\Services\SubscriptionService;
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

        $subscriptionService = app(SubscriptionService::class);
        $tenantId = app(UserGalponService::class)->resolveActiveTenantId($user);

        if ($subscriptionService->isPro($tenantId)) {
            return true;
        }

        return ! $subscriptionService->isAtLimit('gallinas', $tenantId);
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
