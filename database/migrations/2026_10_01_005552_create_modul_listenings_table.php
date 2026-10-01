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
        Schema::create('modul_listenings', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->enum('tingkat_kesulitan', ['Beginner', 'Intermediate', 'Advanced'])->default('Beginner');
            $table->string('tingkat_kelas', 50)->default('Semua Kelas');
            $table->string('audio_file', 255)->nullable();
            $table->string('audio_title', 100)->nullable();
            $table->string('durasi', 20)->nullable();
            $table->longText('transkrip')->nullable();
            $table->json('tujuan_belajar')->nullable();
            $table->integer('xp_reward')->default(50);
            $table->enum('status', ['aktif', 'draft'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_listenings');
    }
};
