<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserExtraGalpon;
use App\Services\AuditService;
use App\Services\UserGalponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserExtraGalponController extends Controller
{
    public function __construct(
        protected UserGalponService $galponService
    ) {}

    public function index(string $id)
    {
        $user = User::query()
            ->where('is_superadmin', false)
            ->findOrFail($id);

        $primaryTenant = $user->tenant_id
            ? Tenant::query()->find($user->tenant_id)
            : null;

        $extraGalpones = UserExtraGalpon::query()
            ->with('tenant')
            ->where('user_id', $user->id)
            ->orderBy('created_at')
            ->get();

        return view('super-admin.users.galpones', compact('user', 'primaryTenant', 'extraGalpones'));
    }

    public function enable(string $id)
    {
        $user = $this->findTenantUser($id);
        $user->update(['extra_galpones_enabled' => true]);

        AuditService::log('user.extra_galpones.enabled', "Galpones extras habilitados para {$user->email}.", [
            'user_id' => $user->id,
        ]);

        return redirect()
            ->route('superadmin.users.galpones', $user->id)
            ->with('ok', 'Función de galpones extras activada para este usuario.');
    }

    public function disable(string $id)
    {
        $user = $this->findTenantUser($id);
        $user->update(['extra_galpones_enabled' => false]);
        $this->galponService->clearActiveGalponSession($user);

        AuditService::log('user.extra_galpones.disabled', "Galpones extras deshabilitados para {$user->email}.", [
            'user_id' => $user->id,
        ]);

        return redirect()
            ->route('superadmin.users.galpones', $user->id)
            ->with('ok', 'Función de galpones extras desactivada. El usuario solo verá su galpón principal.');
    }

    public function store(Request $request, string $id)
    {
        $user = $this->findTenantUser($id);

        if (! $user->extra_galpones_enabled) {
            return redirect()
                ->route('superadmin.users.galpones', $user->id)
                ->withErrors(['name' => 'Primero debes activar la función de galpones extras para este usuario.']);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        $existingIds = $this->galponService->accessibleTenantIds($user);

        DB::transaction(function () use ($user, $data, $existingIds, $request) {
            $tenantId = (string) Str::uuid();

            Tenant::query()->create([
                'id' => $tenantId,
                'name' => $data['name'],
                'status' => 'active',
            ]);

            Subscription::withoutGlobalScopes()->create([
                'tenant_id' => $tenantId,
                'plan' => 'free',
                'status' => 'active',
                'ends_at' => now()->addYear(),
            ]);

            UserExtraGalpon::query()->create([
                'user_id' => $user->id,
                'tenant_id' => $tenantId,
                'label' => $data['label'] ?: $data['name'],
                'granted_by' => $request->user()?->id,
            ]);

            AuditService::log('user.extra_galpon.added', "Galpón extra «{$data['name']}» asignado a {$user->email}.", [
                'user_id' => $user->id,
                'tenant_id' => $tenantId,
            ]);
        });

        return redirect()
            ->route('superadmin.users.galpones', $user->id)
            ->with('ok', 'Galpón extra creado y asignado al usuario.');
    }

    public function destroy(string $id, int $galponId)
    {
        $user = $this->findTenantUser($id);

        $extra = UserExtraGalpon::query()
            ->where('user_id', $user->id)
            ->findOrFail($galponId);

        $tenantId = $extra->tenant_id;
        $extra->delete();

        if (session(UserGalponService::SESSION_KEY) === $tenantId) {
            $this->galponService->clearActiveGalponSession($user);
        }

        AuditService::log('user.extra_galpon.removed', "Acceso al galpón extra removido para {$user->email}.", [
            'user_id' => $user->id,
            'tenant_id' => $tenantId,
        ]);

        return redirect()
            ->route('superadmin.users.galpones', $user->id)
            ->with('ok', 'Acceso al galpón extra eliminado. Los datos del galpón se conservan en el sistema.');
    }

    private function findTenantUser(string $id): User
    {
        return User::query()
            ->where('is_superadmin', false)
            ->findOrFail($id);
    }
}
