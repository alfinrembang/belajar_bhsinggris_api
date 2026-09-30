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
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori'); // Grammar, Reading, Vocabulary, Conversation
            $table->string('tingkat_kelas'); // 10, 11, 12, Semua Kelas
            $table->text('deskripsi_singkat')->nullable();
            $table->longText('penjelasan');
            $table->longText('contoh_teks')->nullable();
            $table->string('gambar_materi')->nullable();
            $table->string('audio_materi')->nullable();
            $table->integer('xp_reward')->default(50);
            $table->string('status')->default('aktif'); // aktif, draft
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
