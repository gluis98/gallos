<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ventas') && ! Schema::hasColumn('ventas', 'gallina_id')) {
            Schema::table('ventas', function (Blueprint $table) {
                $table->unsignedInteger('gallina_id')->nullable()->after('gallo_id');
            });
        }

        if (Schema::hasTable('compra_items')) {
            Schema::table('compra_items', function (Blueprint $table) {
                if (! Schema::hasColumn('compra_items', 'gallo_id')) {
                    $table->unsignedInteger('gallo_id')->nullable()->after('inventario_id');
                }
                if (! Schema::hasColumn('compra_items', 'gallina_id')) {
                    $table->unsignedInteger('gallina_id')->nullable()->after('gallo_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ventas') && Schema::hasColumn('ventas', 'gallina_id')) {
            Schema::table('ventas', function (Blueprint $table) {
                $table->dropColumn('gallina_id');
            });
        }

        if (Schema::hasTable('compra_items')) {
            Schema::table('compra_items', function (Blueprint $table) {
                $cols = array_filter(
                    ['gallina_id', 'gallo_id'],
                    fn ($c) => Schema::hasColumn('compra_items', $c)
                );
                if ($cols) {
                    $table->dropColumn($cols);
                }
            });
        }
    }
};
