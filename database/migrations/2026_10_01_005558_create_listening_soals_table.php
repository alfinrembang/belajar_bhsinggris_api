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
        Schema::create('listening_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_listening_id')->constrained('modul_listenings')->cascadeOnDelete();
            $table->integer('nomor_soal')->default(1);
            $table->text('pertanyaan');
            $table->text('pilihan_a');
            $table->text('pilihan_b');
            $table->text('pilihan_c');
            $table->text('pilihan_d');
            $table->enum('jawaban_benar', ['A', 'B', 'C', 'D']);
            $table->text('pembahasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listening_soals');
    }
};
