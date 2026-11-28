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
        // 1. Master Kosakata Pelafalan (Pronounce Words)
        if (! Schema::hasTable('pronounce_words')) {
            Schema::create('pronounce_words', function (Blueprint $table) {
                $table->id();
                $table->string('word', 100);
                $table->string('phonetic', 100)->nullable();
                $table->string('part_of_speech', 50)->nullable();
                $table->string('meaning', 255);
                $table->string('difficulty', 20)->default('mudah'); // mudah, sedang, sulit
                $table->string('audio_url')->nullable();
                $table->text('tips')->nullable();
                $table->string('example_sentence', 255)->nullable();
                $table->timestamps();
            });
        }

        // 2. Progres Solo Player Siswa
        if (! Schema::hasTable('siswa_pronounce_progres')) {
            Schema::create('siswa_pronounce_progres', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('siswa_id')->index();
                $table->unsignedBigInteger('pronounce_word_id')->index();
                $table->integer('best_accuracy')->default(0); // 0 - 100%
                $table->integer('stars')->default(0); // 1, 2, 3
                $table->integer('xp_didapat')->default(0);
                $table->timestamps();

                $table->unique(['siswa_id', 'pronounce_word_id'], 'unique_siswa_pronounce_word');
            });
        }

        // 3. Room Mabar (Multiplayer) Maksimal 5 Player
        if (! Schema::hasTable('pronounce_rooms')) {
            Schema::create('pronounce_rooms', function (Blueprint $table) {
                $table->id();
                $table->string('code', 10)->unique();
                $table->unsignedBigInteger('host_id')->index();
                $table->string('title', 100)->default('Room Adu Lafal');
                $table->enum('status', ['waiting', 'playing', 'finished'])->default('waiting');
                $table->integer('max_players')->default(5);
                $table->unsignedBigInteger('current_turn_siswa_id')->nullable();
                $table->integer('current_round')->default(1);
                $table->integer('total_rounds')->default(5);
                $table->unsignedBigInteger('current_word_id')->nullable();
                $table->timestamps();
            });
        }

        // 4. Daftar Pemain di Dalam Room
        if (! Schema::hasTable('pronounce_room_players')) {
            Schema::create('pronounce_room_players', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('room_id')->index();
                $table->unsignedBigInteger('siswa_id')->index();
                $table->integer('total_score')->default(0);
                $table->boolean('is_ready')->default(false);
                $table->timestamps();

                $table->unique(['room_id', 'siswa_id'], 'unique_room_siswa');
            });
        }

        // 5. Rekaman Suara VN Ronde & Hasil Penilaian
        if (! Schema::hasTable('pronounce_submissions')) {
            Schema::create('pronounce_submissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('room_id')->index();
                $table->integer('round')->default(1);
                $table->unsignedBigInteger('siswa_id')->index();
                $table->unsignedBigInteger('word_id')->index();
                $table->string('audio_path')->nullable();
                $table->string('audio_url')->nullable();
                $table->integer('ai_accuracy')->nullable();
                $table->decimal('average_score', 4, 1)->default(0.0);
                $table->integer('total_voters')->default(0);
                $table->timestamps();
            });
        }

        // 6. Nilai Voting dari Player Lain (1 - 10)
        if (! Schema::hasTable('pronounce_votes')) {
            Schema::create('pronounce_votes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('submission_id')->index();
                $table->unsignedBigInteger('voter_siswa_id')->index();
                $table->integer('score')->default(8); // 1 - 10
                $table->timestamps();

                $table->unique(['submission_id', 'voter_siswa_id'], 'unique_sub_voter');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pronounce_votes');
        Schema::dropIfExists('pronounce_submissions');
        Schema::dropIfExists('pronounce_room_players');
        Schema::dropIfExists('pronounce_rooms');
        Schema::dropIfExists('siswa_pronounce_progres');
        Schema::dropIfExists('pronounce_words');
    }
};
