<?php

use App\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (! Schema::hasColumn('tenants', 'catalog_token_hash')) {
                $table->string('catalog_token_hash', 64)->nullable()->unique()->after('catalog_enabled');
            }
            if (! Schema::hasColumn('tenants', 'catalog_token_encrypted')) {
                $table->text('catalog_token_encrypted')->nullable()->after('catalog_token_hash');
            }
        });

        if (! Schema::hasColumn('tenants', 'catalog_token')) {
            return;
        }

        Tenant::query()->whereNotNull('catalog_token')->each(function (Tenant $tenant) {
            $plain = $tenant->catalog_token;
            if (! is_string($plain) || $plain === '') {
                return;
            }

            $tenant->catalog_token_hash = hash_hmac('sha256', $plain, config('app.key'));
            try {
                $tenant->catalog_token_encrypted = Crypt::encryptString($plain);
            } catch (\Throwable) {
                $tenant->catalog_token_encrypted = null;
            }
            $tenant->catalog_token = null;
            $tenant->save();
        });

        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'catalog_token')) {
                $table->dropColumn('catalog_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (! Schema::hasColumn('tenants', 'catalog_token')) {
                $table->string('catalog_token', 64)->nullable()->unique();
            }
        });

        if (Schema::hasColumn('tenants', 'catalog_token_hash')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->dropColumn(['catalog_token_hash', 'catalog_token_encrypted']);
            });
        }
    }
};
