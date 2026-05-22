<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('proveedores')) {
            Schema::create('proveedores', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('compras')) {
            Schema::create('compras', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->foreignId('proveedor_id')->constrained('proveedores')->cascadeOnDelete();
                $table->date('fecha_compra');
                $table->decimal('total', 12, 2)->default(0);
                $table->text('observaciones')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('compra_items')) {
            Schema::create('compra_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('compra_id')->constrained('compras')->cascadeOnDelete();
                $table->enum('tipo_ave', ['gallo', 'gallina']);
                $table->string('placa')->nullable();
                $table->string('nombre')->nullable();
                $table->string('marca_nacimiento')->nullable();
                $table->string('color')->nullable();
                $table->decimal('costo', 12, 2);
                $table->string('foto_path')->nullable();
                $table->text('observaciones')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('compra_items');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('proveedores');
    }
};

