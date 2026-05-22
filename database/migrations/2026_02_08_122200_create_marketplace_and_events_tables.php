<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('publicaciones')) {
            Schema::create('publicaciones', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->string('ave_type', 32)->nullable();
                $table->unsignedBigInteger('ave_id')->nullable();
                $table->decimal('precio', 12, 2)->nullable();
                $table->text('descripcion')->nullable();
                $table->boolean('activo')->default(true);
                $table->boolean('destacado')->default(false);
                $table->timestamps();

                $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('mensajes')) {
            Schema::create('mensajes', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->nullable()->index();
                $table->foreignId('publicacion_id')->nullable()->constrained('publicaciones')->nullOnDelete();
                $table->string('nombre')->nullable();
                $table->string('contacto')->nullable();
                $table->text('cuerpo')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('comisiones')) {
            Schema::create('comisiones', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                // ventas.id es increments (INT UNSIGNED); foreignId() sería BIGINT y MySQL 8 rechaza la FK.
                $table->unsignedInteger('venta_id')->nullable();
                $table->decimal('monto', 12, 2);
                $table->string('estado', 32)->default('pendiente');
                $table->timestamps();

                $table->foreign('venta_id')->references('id')->on('ventas')->nullOnDelete();
            });
        }

        if (! Schema::hasTable('evento_aves')) {
            Schema::create('evento_aves', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->string('ave_type', 32);
                $table->unsignedBigInteger('ave_id');
                $table->string('tipo_evento', 64);
                $table->date('fecha');
                $table->text('notas')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('pesaje_aves')) {
            Schema::create('pesaje_aves', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->unsignedInteger('gallo_id')->nullable();
                $table->unsignedInteger('gallina_id')->nullable();
                $table->date('fecha');
                $table->decimal('peso_kg', 8, 3);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('pesaje_aves');
        Schema::dropIfExists('evento_aves');
        Schema::dropIfExists('comisiones');
        Schema::dropIfExists('mensajes');
        Schema::dropIfExists('publicaciones');
    }
};
