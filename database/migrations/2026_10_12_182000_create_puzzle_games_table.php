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
        // 1. Tambahkan kolom koin pada tabel siswas (default 120 koin) jika belum ada
        if (Schema::hasTable('siswas') && !Schema::hasColumn('siswas', 'koin')) {
            Schema::table('siswas', function (Blueprint $table) {
                $table->integer('koin')->default(120)->after('password');
            });
        }

        // 2. Tabel progres level puzzle siswa
        if (!Schema::hasTable('siswa_puzzle_progres')) {
            Schema::create('siswa_puzzle_progres', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('siswa_id')->nullable()->index();
                $table->string('difficulty', 20)->default('mudah'); // mudah, sedang, sulit
                $table->integer('level')->default(1);
                $table->integer('stars')->default(0); // 0, 1, 2, 3
                $table->boolean('is_completed')->default(false);
                $table->string('best_time', 20)->nullable();
                $table->timestamps();

                $table->unique(['siswa_id', 'difficulty', 'level'], 'unique_siswa_diff_lvl');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_puzzle_progres');
        if (Schema::hasTable('siswas') && Schema::hasColumn('siswas', 'koin')) {
            Schema::table('siswas', function (Blueprint $table) {
                $table->dropColumn('koin');
            });
        }
    }
};
