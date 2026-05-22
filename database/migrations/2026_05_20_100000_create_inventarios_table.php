<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('categoria')->nullable();
            $table->string('unidad_medida')->default('unidad'); // unidad, kg, litro, etc.
            $table->decimal('stock_actual', 12, 2)->default(0);
            $table->decimal('costo_unitario', 14, 4)->default(0);  // en USD
            $table->decimal('utilidad_porcentaje', 7, 2)->default(0); // % ganancia
            $table->decimal('precio_venta', 14, 4)->default(0);    // en USD (calculado o manual)
            $table->string('foto_path')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        // Agregar inventario_id y cantidad a compra_items
        Schema::table('compra_items', function (Blueprint $table) {
            if (! Schema::hasColumn('compra_items', 'inventario_id')) {
                $table->unsignedBigInteger('inventario_id')->nullable()->after('tipo_ave');
            }
            if (! Schema::hasColumn('compra_items', 'cantidad')) {
                $table->decimal('cantidad', 10, 2)->default(1)->after('costo');
            }
        });

        // Agregar soporte de inventario a ventas
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('tipo_item')->default('gallo')->after('tipo_venta');
            $table->unsignedBigInteger('inventario_id')->nullable()->after('tipo_item');
            $table->decimal('cantidad', 10, 2)->default(1)->after('inventario_id');
        });

        // Hacer gallo_id nullable en ventas (columna ya existente)
        Schema::table('ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('gallo_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');

        Schema::table('compra_items', function (Blueprint $table) {
            $cols = array_filter(['inventario_id', 'cantidad'], fn ($c) => Schema::hasColumn('compra_items', $c));
            if ($cols) {
                $table->dropColumn($cols);
            }
        });

        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['tipo_item', 'inventario_id', 'cantidad']);
        });
    }
};
