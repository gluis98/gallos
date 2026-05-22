<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Models\UserExtraGalpon;
use Illuminate\Support\Collection;

class UserGalponService
{
    public const SESSION_KEY = 'active_tenant_id';

    /** @return array<int, string> */
    public function accessibleTenantIds(User $user): array
    {
        $ids = [];

        if ($user->tenant_id) {
            $ids[] = (string) $user->tenant_id;
        }

        if ($user->extra_galpones_enabled) {
            UserExtraGalpon::query()
                ->where('user_id', $user->id)
                ->pluck('tenant_id')
                ->each(function ($tenantId) use (&$ids) {
                    $tenantId = (string) $tenantId;
                    if (! in_array($tenantId, $ids, true)) {
                        $ids[] = $tenantId;
                    }
                });
        }

        return $ids;
    }

    public function canAccessTenant(User $user, string $tenantId): bool
    {
        return in_array($tenantId, $this->accessibleTenantIds($user), true);
    }

    public function resolveActiveTenantId(User $user): ?string
    {
        $ids = $this->accessibleTenantIds($user);
        if ($ids === []) {
            return null;
        }

        $sessionId = session(self::SESSION_KEY);
        if ($sessionId && $this->canAccessTenant($user, (string) $sessionId)) {
            return (string) $sessionId;
        }

        return $user->tenant_id ? (string) $user->tenant_id : $ids[0];
    }

    public function resolveActiveTenant(User $user): ?Tenant
    {
        $id = $this->resolveActiveTenantId($user);

        return $id ? Tenant::query()->find($id) : null;
    }

    /** @return Collection<int, array{id: string, name: string, is_primary: bool, is_active: bool}> */
    public function galponOptionsForNavbar(User $user): Collection
    {
        if (! $user->extra_galpones_enabled) {
            return collect();
        }

        $activeId = $this->resolveActiveTenantId($user);
        $primaryId = $user->tenant_id ? (string) $user->tenant_id : null;

        $extras = UserExtraGalpon::query()
            ->with('tenant')
            ->where('user_id', $user->id)
            ->orderBy('created_at')
            ->get();

        $options = collect();

        if ($primaryId) {
            $primary = Tenant::query()->find($primaryId);
            if ($primary) {
                $options->push([
                    'id' => $primaryId,
                    'name' => $primary->name ?: 'Galpón principal',
                    'is_primary' => true,
                    'is_active' => $activeId === $primaryId,
                ]);
            }
        }

        foreach ($extras as $extra) {
            if (! $extra->tenant || $extra->tenant_id === $primaryId) {
                continue;
            }
            $tid = (string) $extra->tenant_id;
            $options->push([
                'id' => $tid,
                'name' => $extra->displayName(),
                'is_primary' => false,
                'is_active' => $activeId === $tid,
            ]);
        }

        return $options->unique('id')->values();
    }

    public function showsGalponSwitcher(User $user): bool
    {
        return $user->extra_galpones_enabled
            && $this->galponOptionsForNavbar($user)->count() > 1;
    }

    public function switchGalpon(User $user, string $tenantId): void
    {
        if (! $this->canAccessTenant($user, $tenantId)) {
            abort(403, 'No tienes acceso a este galpón.');
        }

        session([self::SESSION_KEY => $tenantId]);

        if (tenancy()->initialized) {
            tenancy()->end();
        }

        $tenant = Tenant::query()->findOrFail($tenantId);
        if ($tenant->status !== 'active') {
            abort(403, 'Este galpón está suspendido.');
        }

        tenancy()->initialize($tenant);
    }

    public function clearActiveGalponSession(User $user): void
    {
        session()->forget(self::SESSION_KEY);
    }
}
