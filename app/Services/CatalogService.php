<?php

namespace App\Services;

use App\Models\Gallo;
use App\Models\Tenant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

class CatalogService
{
    /** Longitud del secreto en la URL (256 bits en hexadecimal). */
    public const TOKEN_BYTES = 32;

    public function findTenantByToken(string $token): ?Tenant
    {
        $token = strtolower(trim($token));
        if (! $this->isValidTokenFormat($token)) {
            return null;
        }

        $hash = $this->hashToken($token);

        return Tenant::query()
            ->where('catalog_token_hash', $hash)
            ->where('catalog_enabled', true)
            ->where('status', 'active')
            ->first();
    }

    public function ensureToken(Tenant $tenant): array
    {
        $plain = $this->decryptStoredToken($tenant);
        if ($plain !== null) {
            return ['tenant' => $tenant, 'plain_token' => $plain];
        }

        return $this->assignNewToken($tenant);
    }

    public function regenerateToken(Tenant $tenant): array
    {
        return $this->assignNewToken($tenant);
    }

    public function publicUrl(Tenant $tenant): string
    {
        ['plain_token' => $plain] = $this->ensureToken($tenant);

        return route('catalog.public', ['token' => $plain]);
    }

    /**
     * @return Collection<int, Gallo>
     */
    public function gallosForCatalog(Tenant $tenant): Collection
    {
        tenancy()->initialize($tenant);

        return Gallo::query()
            ->with([
                'gallos_imagenes',
                'gallos_hijos.padre:id,placa,nombre,color',
                'gallos_hijos.madre:id,placa,nombre,color',
            ])
            ->whereNotIn('estatus', ['Vendido', 'Fallecido'])
            ->orderBy('placa')
            ->get();
    }

    public function infoForUser($user): array
    {
        $tenantId = tenancy()->initialized
            ? tenant('id')
            : app(\App\Services\UserGalponService::class)->resolveActiveTenantId($user);

        $tenant = $tenantId ? Tenant::query()->find($tenantId) : null;
        if (! $tenant) {
            return [
                'enabled' => false,
                'url' => null,
                'count' => 0,
            ];
        }

        $this->ensureToken($tenant);
        tenancy()->initialize($tenant);
        $count = Gallo::query()
            ->whereNotIn('estatus', ['Vendido', 'Fallecido'])
            ->count();

        return [
            'enabled' => (bool) $tenant->catalog_enabled,
            'url' => $this->publicUrl($tenant->fresh()),
            'count' => $count,
            'tenant_name' => $tenant->name,
            'token_hint' => 'Enlace protegido con clave aleatoria (no usa ID de cuenta).',
        ];
    }

    /**
     * @return array{tenant: Tenant, plain_token: string}
     */
    private function assignNewToken(Tenant $tenant): array
    {
        do {
            $plain = bin2hex(random_bytes(self::TOKEN_BYTES));
            $hash = $this->hashToken($plain);
        } while (Tenant::query()->where('catalog_token_hash', $hash)->exists());

        $tenant->catalog_token_hash = $hash;
        $tenant->catalog_token_encrypted = Crypt::encryptString($plain);
        $tenant->catalog_enabled = $tenant->catalog_enabled ?? true;
        $tenant->save();

        return ['tenant' => $tenant->fresh(), 'plain_token' => $plain];
    }

    private function decryptStoredToken(Tenant $tenant): ?string
    {
        if (empty($tenant->catalog_token_hash) || empty($tenant->catalog_token_encrypted)) {
            return null;
        }

        try {
            $plain = Crypt::decryptString($tenant->catalog_token_encrypted);
        } catch (\Throwable) {
            return null;
        }

        if (! $this->isValidTokenFormat($plain)) {
            return null;
        }

        if (! hash_equals($tenant->catalog_token_hash, $this->hashToken($plain))) {
            return null;
        }

        return $plain;
    }

    private function hashToken(string $token): string
    {
        return hash_hmac('sha256', strtolower($token), config('app.key'));
    }

    private function isValidTokenFormat(string $token): bool
    {
        return (bool) preg_match('/^[a-f0-9]{'.(self::TOKEN_BYTES * 2).'}$/', $token);
    }
}
