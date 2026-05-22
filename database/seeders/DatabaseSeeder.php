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

        Subscription::query()->create([
            'tenant_id' => $tenantId,
            'plan' => 'free',
            'status' => 'active',
            'ends_at' => now()->addYear(),
        ]);

        User::query()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenantId,
            'is_superadmin' => false,
        ]);

        User::query()->create([
            'name' => 'Super Admin',
            'email' => 'super@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => null,
            'is_superadmin' => true,
        ]);
    }
}
