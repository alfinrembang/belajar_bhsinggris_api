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
        Schema::table('pengaturan_akademiks', function (Blueprint $table) {
            $table->unsignedTinyInteger('kkm_default')->default(75)->after('is_periode_kenaikan_aktif');
            $table->unsignedSmallInteger('durasi_kuis_default')->default(20)->after('kkm_default');
            $table->boolean('is_leaderboard_aktif')->default(true)->after('durasi_kuis_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_akademiks', function (Blueprint $table) {
            $table->dropColumn(['kkm_default', 'durasi_kuis_default', 'is_leaderboard_aktif']);
        });
    }
};
