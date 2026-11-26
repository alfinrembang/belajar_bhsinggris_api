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
        if (Schema::hasColumn('users', 'kelas_diampu')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('kelas_diampu');
            });
        }

        Schema::table('materis', function (Blueprint $table) {
            $table->string('tingkat_kelas', 255)->change();
            $table->text('target_rombel')->nullable()->change();
        });

        foreach (['modul_listenings', 'quizzes'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('tingkat_kelas', 255)->default('Semua Kelas')->change();
                $table->text('target_rombel')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('users', 'kelas_diampu')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('kelas_diampu')->nullable()->after('nip');
            });
        }

        foreach (['materis', 'modul_listenings', 'quizzes'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('target_rombel', 255)->nullable()->change();
            });
        }
    }
};
