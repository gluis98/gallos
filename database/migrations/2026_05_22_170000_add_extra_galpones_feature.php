<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'extra_galpones_enabled')) {
                $table->boolean('extra_galpones_enabled')->default(false)->after('is_superadmin');
            }
        });

        if (! Schema::hasTable('user_extra_galpones')) {
            Schema::create('user_extra_galpones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('tenant_id');
                $table->string('label')->nullable();
                $table->unsignedBigInteger('granted_by')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'tenant_id']);
                $table->index('tenant_id');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                $table->foreign('granted_by')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_extra_galpones');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'extra_galpones_enabled')) {
                $table->dropColumn('extra_galpones_enabled');
            }
        });
    }
};
