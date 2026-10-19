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
        // 1. Tambah kolom kelas_diampu pada tabel users (akun guru)
        Schema::table('users', function (Blueprint $table) {
            $table->json('kelas_diampu')->nullable()->after('nip');
        });

        // 2. Tambah kepemilikan user_id dan target_rombel pada tabel materis
        Schema::table('materis', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('target_rombel')->nullable()->after('tingkat_kelas');
        });

        // 3. Tambah kepemilikan user_id dan target_rombel pada tabel modul_listenings
        Schema::table('modul_listenings', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('target_rombel')->nullable()->after('tingkat_kelas');
        });

        // 4. Tambah kepemilikan user_id dan target_rombel pada tabel quizzes
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('target_rombel')->nullable()->after('tingkat_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'target_rombel']);
        });

        Schema::table('modul_listenings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'target_rombel']);
        });

        Schema::table('materis', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'target_rombel']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kelas_diampu');
        });
    }
};
