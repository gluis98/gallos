<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = (string) Str::uuid();

        Tenant::query()->create([
            'id' => $tenantId,
            'name' => 'Tenant demo',
            'status' => 'active',
        ]);

        Subscription::withoutGlobalScopes()->create([
            'tenant_id' => $tenantId,
            'plan' => 'free',
            'status' => 'active',
            'ends_at' => now()->addYear(),
        ]);

        // Usuario tenant de demostración
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador Demo',
                'password' => Hash::make('password'),
                'tenant_id' => $tenantId,
                'is_superadmin' => false,
            ]
        );

        // Super administrador del sistema
        User::query()->updateOrCreate(
            ['email' => 'adsys.sistemas.dev@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Warcraft$2424'),
                'tenant_id' => null,
                'is_superadmin' => true,
            ]
        );
    }
}
