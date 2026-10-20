<?php

namespace App\Policies;

use App\Models\Gallo;
use App\Models\User;
use App\Services\SubscriptionService;
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

        $subscriptionService = app(SubscriptionService::class);
        $tenantId = app(UserGalponService::class)->resolveActiveTenantId($user);

        if ($subscriptionService->isPro($tenantId)) {
            return true;
        }

        return ! $subscriptionService->isAtLimit('gallos', $tenantId);
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
