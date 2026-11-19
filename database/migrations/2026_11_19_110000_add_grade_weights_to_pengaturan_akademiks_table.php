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
            $table->unsignedInteger('bobot_materi')->default(30)->after('kkm_default');
            $table->unsignedInteger('bobot_listening')->default(30)->after('bobot_materi');
            $table->unsignedInteger('bobot_quiz')->default(40)->after('bobot_listening');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_akademiks', function (Blueprint $table) {
            $table->dropColumn(['bobot_materi', 'bobot_listening', 'bobot_quiz']);
        });
    }
};
