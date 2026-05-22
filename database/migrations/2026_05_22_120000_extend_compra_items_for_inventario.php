<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('compra_items')) {
            return;
        }

        Schema::table('compra_items', function (Blueprint $table) {
            if (! Schema::hasColumn('compra_items', 'cantidad')) {
                $table->decimal('cantidad', 10, 2)->default(1)->after('costo');
            }
        });

        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE compra_items MODIFY tipo_ave ENUM('gallo', 'gallina', 'inventario') NOT NULL");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('compra_items')) {
            return;
        }

        Schema::table('compra_items', function (Blueprint $table) {
            if (Schema::hasColumn('compra_items', 'cantidad')) {
                $table->dropColumn('cantidad');
            }
        });

        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE compra_items MODIFY tipo_ave ENUM('gallo', 'gallina') NOT NULL");
        }
    }
};
