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
        // 1. Tabel Master Soal & Kosakata Game Memancing (Word Fishing)
        if (! Schema::hasTable('fishing_soals')) {
            Schema::create('fishing_soals', function (Blueprint $table) {
                $table->id();
                $table->integer('level')->default(1);
                $table->string('difficulty', 20)->default('mudah'); // mudah, sedang, sulit
                $table->string('word', 100); // e.g. "GENEROUS"
                $table->string('part_of_speech', 50); // e.g. "Adjective (Kata Sifat)", "Verb (Kata Kerja)"
                $table->string('meaning', 255); // e.g. "Dermawan / Suka Memberi"
                $table->json('options'); // JSON array berisi 4 pilihan jawaban
                $table->text('explanation')->nullable(); // Penjelasan edukasi
                $table->text('example_sentence')->nullable(); // Contoh kalimat praktis
                $table->integer('pull_energy')->default(20); // Daya tarik ikan (energi)
                $table->timestamps();
            });
        }

        // 2. Tabel Progres & Koleksi Memancing Siswa
        if (! Schema::hasTable('siswa_fishing_progres')) {
            Schema::create('siswa_fishing_progres', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('siswa_id')->nullable()->index();
                $table->string('difficulty', 20)->default('mudah');
                $table->integer('level')->default(1);
                $table->integer('stars')->default(0); // 0, 1, 2, 3
                $table->integer('high_score')->default(0);
                $table->string('fish_name', 100)->nullable(); // Nama ikan yang ditangkap di level ini
                $table->string('fish_emoji', 20)->default('🐟');
                $table->boolean('is_completed')->default(false);
                $table->timestamps();

                $table->unique(['siswa_id', 'difficulty', 'level'], 'unique_siswa_fish_diff_lvl');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_fishing_progres');
        Schema::dropIfExists('fishing_soals');
    }
};
