<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ventas') || ! Schema::hasTable('clients')) {
            return;
        }

        if (! Schema::hasColumn('ventas', 'cliente_id')) {
            return;
        }

        if ($this->ventasClienteForeignKeyExists()) {
            return;
        }

        Schema::table('ventas', function (Blueprint $table) {
            $table->foreign('cliente_id')->references('id')->on('clients')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ventas') || ! Schema::hasColumn('ventas', 'cliente_id')) {
            return;
        }

        if (! $this->ventasClienteForeignKeyExists()) {
            return;
        }

        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
        });
    }

    private function ventasClienteForeignKeyExists(): bool
    {
        $row = DB::selectOne(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME IS NOT NULL',
            ['ventas', 'cliente_id']
        );

        return $row !== null;
    }
};
