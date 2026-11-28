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
        // 1. Master Soal Susun Kalimat (Sentence Builder)
        if (! Schema::hasTable('sentence_soals')) {
            Schema::create('sentence_soals', function (Blueprint $table) {
                $table->id();
                $table->integer('level')->default(1);
                $table->string('difficulty', 20)->default('mudah'); // mudah, sedang, sulit
                $table->string('sentence', 255); // Kalimat bahasa Inggris utuh
                $table->string('meaning', 255); // Arti bahasa Indonesia
                $table->string('tenses_category', 100)->default('General Grammar'); // e.g. "Simple Present", "Past Tense"
                $table->text('grammar_tips')->nullable(); // Tips edukasi tata bahasa
                $table->string('audio_url', 255)->nullable();
                $table->integer('points')->default(100);
                $table->timestamps();
            });
        }

        // 2. Progres Solo Player Siswa Game Susun Kalimat
        if (! Schema::hasTable('siswa_sentence_progres')) {
            Schema::create('siswa_sentence_progres', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('siswa_id')->nullable()->index();
                $table->string('difficulty', 20)->default('mudah');
                $table->integer('level')->default(1);
                $table->integer('stars')->default(0); // 1, 2, 3
                $table->integer('high_score')->default(0);
                $table->boolean('is_completed')->default(false);
                $table->timestamps();

                $table->unique(['siswa_id', 'difficulty', 'level'], 'unique_siswa_sentence_lvl_diff');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_sentence_progres');
        Schema::dropIfExists('sentence_soals');
    }
};
