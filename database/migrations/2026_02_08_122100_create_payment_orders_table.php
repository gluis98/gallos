<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 8)->default('USD');
            $table->string('method', 32);
            $table->string('reference')->nullable();
            $table->string('status', 32)->default('pendiente');
            $table->string('proof_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_orders');
    }
};
