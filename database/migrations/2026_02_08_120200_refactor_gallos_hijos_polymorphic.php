<?php

use App\Models\Gallo;
use App\Models\Gallina;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('gallos_hijos')) {
            return;
        }

        if (! Schema::hasColumn('gallos_hijos', 'hijo_id') && Schema::hasColumn('gallos_hijos', 'hijoable_id')) {
            return;
        }

        Schema::table('gallos_hijos', function (Blueprint $table) {
            if (! Schema::hasColumn('gallos_hijos', 'hijoable_type')) {
                $table->string('hijoable_type')->nullable()->after('madre_id');
            }
            if (! Schema::hasColumn('gallos_hijos', 'hijoable_id')) {
                $table->unsignedBigInteger('hijoable_id')->nullable()->after('hijoable_type');
            }
            if (! Schema::hasColumn('gallos_hijos', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->nullable()->index();
            }
            if (! Schema::hasColumn('gallos_hijos', 'created_at')) {
                $table->timestamps();
            }
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql' && Schema::hasColumn('gallos_hijos', 'hijo_id')) {
            $foreignKeys = DB::select(
                'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL',
                ['gallos_hijos', 'hijo_id']
            );
            foreach ($foreignKeys as $fk) {
                DB::statement('ALTER TABLE gallos_hijos DROP FOREIGN KEY `'.$fk->CONSTRAINT_NAME.'`');
            }
        }

        $rows = DB::table('gallos_hijos')->get();
        foreach ($rows as $r) {
            if (empty($r->hijo_id)) {
                continue;
            }
            $rawTipo = $r->tipo ?? 'Gallo';
            $slug = strtolower($rawTipo) === 'gallina' ? 'gallina' : 'gallo';
            $class = $slug === 'gallina' ? Gallina::class : Gallo::class;
            DB::table('gallos_hijos')->where('id', $r->id)->update([
                'hijoable_type' => $class,
                'hijoable_id' => $r->hijo_id,
                'tipo' => $slug,
            ]);
        }

        Schema::table('gallos_hijos', function (Blueprint $table) {
            if (Schema::hasColumn('gallos_hijos', 'hijo_id')) {
                $table->dropColumn('hijo_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('gallos_hijos')) {
            return;
        }

        Schema::table('gallos_hijos', function (Blueprint $table) {
            if (! Schema::hasColumn('gallos_hijos', 'hijo_id')) {
                $table->unsignedBigInteger('hijo_id')->nullable()->after('madre_id');
            }
        });
    }
};
