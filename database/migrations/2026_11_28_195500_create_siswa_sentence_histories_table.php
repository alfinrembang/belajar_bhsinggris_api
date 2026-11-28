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
        if (! Schema::hasTable('siswa_sentence_histories')) {
            Schema::create('siswa_sentence_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('siswa_id')->index();
                $table->unsignedBigInteger('sentence_soal_id')->index();
                $table->boolean('is_correct')->default(true);
                $table->integer('score')->default(0);
                $table->timestamps();

                $table->unique(['siswa_id', 'sentence_soal_id'], 'uniq_siswa_soal_history');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_sentence_histories');
    }
};
