<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('app_settings')) {
            Schema::create('app_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 64)->unique();
                $table->json('value')->nullable();
                $table->timestamps();
            });
        }

        // Migrar sa_settings.json existente a BD (una sola vez)
        if (Storage::disk('local')->exists('sa_settings.json')) {
            $raw = Storage::disk('local')->get('sa_settings.json');
            $data = json_decode($raw, true);
            if (is_array($data) && ! DB::table('app_settings')->where('key', 'sa_settings')->exists()) {
                DB::table('app_settings')->insert([
                    'key' => 'sa_settings',
                    'value' => json_encode($data, JSON_UNESCAPED_UNICODE),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
