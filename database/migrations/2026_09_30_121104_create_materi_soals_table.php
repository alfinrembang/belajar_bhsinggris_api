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
        Schema::create('materi_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materis')->onDelete('cascade');
            $table->string('tipe_soal')->default('pilgan'); // pilgan, listening
            $table->text('pertanyaan');
            $table->string('audio_soal')->nullable(); // audio khusus soal listening jika ada
            $table->string('pilihan_a');
            $table->string('pilihan_b');
            $table->string('pilihan_c');
            $table->string('pilihan_d');
            $table->string('kunci_jawaban', 5); // A, B, C, D
            $table->text('pembahasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_soals');
    }
};
