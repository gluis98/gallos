<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('marketplace_orders')) {
            Schema::create('marketplace_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('publicacion_id')->constrained('publicaciones')->cascadeOnDelete();
                $table->string('buyer_nombre');
                $table->string('buyer_email');
                $table->string('buyer_telefono')->nullable();
                $table->string('buyer_pais')->nullable();
                $table->string('buyer_token', 64)->unique();
                $table->decimal('precio_acordado', 12, 2)->nullable();
                // pendiente | en_negociacion | completada | cancelada
                $table->string('status', 32)->default('pendiente');
                $table->timestamp('rated_by_buyer_at')->nullable();
                $table->timestamp('rated_by_seller_at')->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('marketplace_chats')) {
            Schema::create('marketplace_chats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('marketplace_orders')->cascadeOnDelete();
                // buyer | seller
                $table->string('sender_type', 16)->default('buyer');
                $table->string('sender_nombre');
                $table->text('mensaje')->nullable();
                $table->string('adjunto_path')->nullable();
                $table->string('adjunto_nombre')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('marketplace_ratings')) {
            Schema::create('marketplace_ratings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('marketplace_orders')->cascadeOnDelete();
                // buyer | seller
                $table->string('rated_by', 16);
                $table->unsignedTinyInteger('score');
                $table->text('comentario')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_ratings');
        Schema::dropIfExists('marketplace_chats');
        Schema::dropIfExists('marketplace_orders');
    }
};
