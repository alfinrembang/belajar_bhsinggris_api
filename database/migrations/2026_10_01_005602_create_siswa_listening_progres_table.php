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
        Schema::create('siswa_listening_progres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('modul_listening_id')->constrained('modul_listenings')->cascadeOnDelete();
            $table->enum('status', ['belum_mulai', 'selesai'])->default('selesai');
            $table->integer('skor')->default(100);
            $table->integer('xp_didapat')->default(50);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'modul_listening_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_listening_progres');
    }
};
