<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('gallos')) {
            Schema::create('gallos', function (Blueprint $table) {
                $table->increments('id');
                $table->string('tenant_id')->nullable()->index();
                $table->string('nombre')->nullable();
                $table->string('placa')->default('');
                $table->string('marca')->nullable();
                $table->string('anillo')->nullable();
                $table->string('marca_nacimiento')->nullable();
                $table->string('marca_federacion')->nullable();
                $table->string('color')->nullable();
                $table->string('color_alternativo')->nullable();
                $table->string('cresta')->nullable();
                $table->string('fecha_nacimiento')->nullable();
                $table->string('luna')->nullable();
                $table->string('peleas')->nullable();
                $table->text('observaciones')->nullable();
                $table->string('estatus')->default('Activo');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('gallinas')) {
            Schema::create('gallinas', function (Blueprint $table) {
                $table->increments('id');
                $table->string('tenant_id')->nullable()->index();
                $table->unsignedInteger('padre_id')->nullable();
                $table->unsignedInteger('madre_id')->nullable();
                $table->string('nombre')->nullable();
                $table->string('placa')->default('');
                $table->string('marca')->nullable();
                $table->string('anillo')->nullable();
                $table->string('marca_nacimiento')->nullable();
                $table->string('marca_federacion')->nullable();
                $table->string('color')->nullable();
                $table->string('color_alternativo')->nullable()->default('');
                $table->string('cresta')->nullable();
                $table->string('fecha_nacimiento')->nullable();
                $table->string('luna')->nullable();
                $table->text('observaciones')->nullable();
                $table->string('estatus')->default('Activa');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('gallos_hijos')) {
            Schema::create('gallos_hijos', function (Blueprint $table) {
                $table->increments('id');
                $table->string('tenant_id')->nullable()->index();
                $table->unsignedInteger('padre_id')->nullable();
                $table->unsignedInteger('madre_id')->nullable();
                $table->string('hijoable_type')->nullable();
                $table->unsignedBigInteger('hijoable_id')->nullable();
                $table->string('tipo', 32)->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('gallos_imagenes')) {
            Schema::create('gallos_imagenes', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('gallo_id');
                $table->text('imagen')->nullable();
                $table->foreign('gallo_id')->references('id')->on('gallos')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('gallinas_imagenes')) {
            Schema::create('gallinas_imagenes', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('gallina_id');
                $table->text('imagen')->nullable();
                $table->foreign('gallina_id')->references('id')->on('gallinas')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('ventas')) {
            Schema::create('ventas', function (Blueprint $table) {
                $table->increments('id');
                $table->string('tenant_id')->nullable()->index();
                $table->unsignedInteger('gallo_id')->nullable();
                $table->foreignId('cliente_id')->nullable()->constrained('clients')->nullOnDelete();
                $table->date('fecha')->nullable();
                $table->decimal('precio', 12, 2)->nullable();
                $table->string('tipo_venta', 64)->default('directa');
                $table->text('observaciones')->nullable();
                $table->string('estatus')->default('Finalizada');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('gallinas_imagenes');
        Schema::dropIfExists('gallos_imagenes');
        Schema::dropIfExists('gallos_hijos');
        Schema::dropIfExists('gallinas');
        Schema::dropIfExists('gallos');
    }
};
