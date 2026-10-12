<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan_akademiks', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran', 20)->default('2026/2027');
            $table->boolean('is_periode_kenaikan_aktif')->default(false);
            $table->string('keterangan', 255)->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_akademiks');
    }
};
