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
        // 1. Tabel Paket Kuis
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori', 100)->default('General');
            $table->string('tingkat_kelas', 50)->default('Semua Kelas');
            $table->string('kode_room', 20)->unique();
            $table->integer('durasi_menit')->default(15);
            $table->text('deskripsi')->nullable();
            $table->integer('xp_reward')->default(60);
            $table->enum('status', ['aktif', 'draft'])->default('aktif');
            $table->timestamps();
        });

        // 2. Tabel Butir Soal Kuis (Pilgan, Reading, Listening)
        Schema::create('quiz_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->integer('nomor_soal')->default(1);
            $table->enum('tipe_soal', ['pilgan', 'reading', 'listening'])->default('pilgan');
            $table->text('reading_passage')->nullable();
            $table->string('audio_file')->nullable();
            $table->string('audio_title')->nullable();
            $table->string('audio_duration', 50)->nullable();
            $table->text('pertanyaan');
            $table->text('pilihan_a');
            $table->text('pilihan_b');
            $table->text('pilihan_c');
            $table->text('pilihan_d');
            $table->char('kunci_jawaban', 2)->default('A');
            $table->text('pembahasan')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Riwayat Pengerjaan Kuis Siswa
        Schema::create('siswa_quiz_progres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->integer('skor')->default(0);
            $table->integer('jumlah_benar')->default(0);
            $table->integer('total_soal')->default(0);
            $table->integer('xp_didapat')->default(0);
            $table->json('jawaban_detail')->nullable();
            $table->integer('waktu_pengerjaan_detik')->nullable();
            $table->enum('status', ['selesai', 'sedang_mengerjakan'])->default('selesai');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['siswa_id', 'quiz_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_quiz_progres');
        Schema::dropIfExists('quiz_soals');
        Schema::dropIfExists('quizzes');
    }
};
