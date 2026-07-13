<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacunaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ave_id');
            $table->enum('ave_type', ['gallo', 'gallina']);
            $table->string('vacuna');
            $table->string('descripcion')->nullable();
            $table->string('dosis', 60)->nullable();
            $table->string('via_administracion', 80)->nullable();
            $table->date('fecha_aplicacion');
            $table->date('proxima_fecha')->nullable();
            $table->string('lote', 80)->nullable();
            $table->string('veterinario', 120)->nullable();
            $table->text('notas')->nullable();
            $table->string('tenant_id', 36)->nullable()->index();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['ave_id', 'ave_type']);
            $table->index('fecha_aplicacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacunaciones');
    }
};
