<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (! Schema::hasColumn('tenants', 'catalog_token')) {
                $table->string('catalog_token', 64)->nullable()->unique()->after('status');
            }
            if (! Schema::hasColumn('tenants', 'catalog_enabled')) {
                $table->boolean('catalog_enabled')->default(true)->after('catalog_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'catalog_enabled')) {
                $table->dropColumn('catalog_enabled');
            }
            if (Schema::hasColumn('tenants', 'catalog_token')) {
                $table->dropColumn('catalog_token');
            }
        });
    }
};
