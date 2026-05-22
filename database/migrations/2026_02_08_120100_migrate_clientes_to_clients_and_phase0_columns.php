<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clientes') && Schema::hasTable('clients')) {
            $exists = DB::table('clients')->exists();
            if (! $exists) {
                $rows = DB::table('clientes')->get();
                foreach ($rows as $row) {
                    DB::table('clients')->insert([
                        'name' => $row->nombre ?? 'Cliente',
                        'phone' => $row->telefono,
                        'email' => null,
                        'tenant_id' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        if (Schema::hasTable('gallos')) {
            Schema::table('gallos', function (Blueprint $table) {
                if (! Schema::hasColumn('gallos', 'tenant_id')) {
                    $table->string('tenant_id')->nullable()->after('id')->index();
                }
                if (! Schema::hasColumn('gallos', 'nombre')) {
                    $table->string('nombre')->nullable()->after('tenant_id');
                }
                if (! Schema::hasColumn('gallos', 'marca')) {
                    $table->string('marca')->nullable()->after('placa');
                }
                if (! Schema::hasColumn('gallos', 'anillo')) {
                    $table->string('anillo')->nullable()->after('marca');
                }
                if (! Schema::hasColumn('gallos', 'created_at')) {
                    $table->timestamps();
                }
            });

            if (Schema::hasColumn('gallos', 'marca_nacimiento') && Schema::hasColumn('gallos', 'marca')) {
                DB::statement('UPDATE gallos SET marca = marca_nacimiento WHERE marca IS NULL AND marca_nacimiento IS NOT NULL');
            }
            if (Schema::hasColumn('gallos', 'marca_federacion') && Schema::hasColumn('gallos', 'anillo')) {
                DB::statement('UPDATE gallos SET anillo = marca_federacion WHERE anillo IS NULL AND marca_federacion IS NOT NULL');
            }
        }

        if (Schema::hasTable('gallinas')) {
            Schema::table('gallinas', function (Blueprint $table) {
                if (! Schema::hasColumn('gallinas', 'tenant_id')) {
                    $table->string('tenant_id')->nullable()->after('id')->index();
                }
                if (! Schema::hasColumn('gallinas', 'nombre')) {
                    $table->string('nombre')->nullable()->after('tenant_id');
                }
                if (! Schema::hasColumn('gallinas', 'marca')) {
                    $table->string('marca')->nullable()->after('placa');
                }
                if (! Schema::hasColumn('gallinas', 'anillo')) {
                    $table->string('anillo')->nullable()->after('marca');
                }
                if (! Schema::hasColumn('gallinas', 'estatus')) {
                    $table->string('estatus')->default('Activa');
                }
                if (! Schema::hasColumn('gallinas', 'color_alternativo')) {
                    $table->string('color_alternativo')->nullable()->default('');
                }
                if (! Schema::hasColumn('gallinas', 'created_at')) {
                    $table->timestamps();
                }
            });

            if (Schema::hasColumn('gallinas', 'marca_nacimiento') && Schema::hasColumn('gallinas', 'marca')) {
                DB::statement('UPDATE gallinas SET marca = marca_nacimiento WHERE marca IS NULL AND marca_nacimiento IS NOT NULL');
            }
            if (Schema::hasColumn('gallinas', 'marca_federacion') && Schema::hasColumn('gallinas', 'anillo')) {
                DB::statement('UPDATE gallinas SET anillo = marca_federacion WHERE anillo IS NULL AND marca_federacion IS NOT NULL');
            }
        }

        if (Schema::hasTable('ventas')) {
            Schema::table('ventas', function (Blueprint $table) {
                if (! Schema::hasColumn('ventas', 'tenant_id')) {
                    $table->string('tenant_id')->nullable()->after('id')->index();
                }
                if (! Schema::hasColumn('ventas', 'cliente_id')) {
                    $table->unsignedBigInteger('cliente_id')->nullable()->after('gallo_id')->index();
                }
                if (! Schema::hasColumn('ventas', 'fecha')) {
                    $table->date('fecha')->nullable()->after('cliente_id');
                }
                if (! Schema::hasColumn('ventas', 'precio')) {
                    $table->decimal('precio', 12, 2)->nullable()->after('fecha');
                }
                if (! Schema::hasColumn('ventas', 'tipo_venta')) {
                    $table->string('tipo_venta', 64)->default('directa')->after('precio');
                }
            });

            if (Schema::hasColumn('ventas', 'monto') && Schema::hasColumn('ventas', 'precio')) {
                DB::statement('UPDATE ventas SET precio = monto WHERE precio IS NULL AND monto IS NOT NULL');
            }
            if (Schema::hasColumn('ventas', 'created_at') && Schema::hasColumn('ventas', 'fecha')) {
                DB::statement('UPDATE ventas SET fecha = DATE(created_at) WHERE fecha IS NULL AND created_at IS NOT NULL');
            }

            if (Schema::hasTable('clients') && Schema::hasColumn('ventas', 'nombre_cliente') && Schema::hasColumn('ventas', 'cliente_id')) {
                $ventas = DB::table('ventas')->whereNull('cliente_id')->get();
                foreach ($ventas as $v) {
                    $name = $v->nombre_cliente ?: 'Cliente';
                    $clientId = DB::table('clients')->insertGetId([
                        'name' => $name,
                        'phone' => $v->telefono ?? null,
                        'email' => null,
                        'tenant_id' => $v->tenant_id ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    DB::table('ventas')->where('id', $v->id)->update(['cliente_id' => $clientId]);
                }
            }

            Schema::table('ventas', function (Blueprint $table) {
                if (Schema::hasColumn('ventas', 'nombre_cliente')) {
                    $table->dropColumn('nombre_cliente');
                }
                if (Schema::hasColumn('ventas', 'telefono')) {
                    $table->dropColumn('telefono');
                }
                if (Schema::hasColumn('ventas', 'monto')) {
                    $table->dropColumn('monto');
                }
                if (Schema::hasColumn('ventas', 'update_at')) {
                    $table->dropColumn('update_at');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ventas')) {
            Schema::table('ventas', function (Blueprint $table) {
                if (! Schema::hasColumn('ventas', 'nombre_cliente')) {
                    $table->string('nombre_cliente')->default('');
                }
                if (! Schema::hasColumn('ventas', 'telefono')) {
                    $table->string('telefono')->default('');
                }
                if (! Schema::hasColumn('ventas', 'monto')) {
                    $table->decimal('monto', 12, 2)->nullable();
                }
            });
        }
    }
};
