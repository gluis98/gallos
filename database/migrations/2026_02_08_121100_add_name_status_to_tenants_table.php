<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (! Schema::hasColumn('tenants', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (! Schema::hasColumn('tenants', 'status')) {
                $table->string('status', 32)->default('active')->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['name', 'status']);
        });
    }
};
