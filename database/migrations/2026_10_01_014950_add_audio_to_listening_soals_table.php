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
        Schema::table('listening_soals', function (Blueprint $table) {
            $table->string('audio_file')->nullable()->after('pertanyaan');
            $table->string('audio_title')->nullable()->after('audio_file');
            $table->string('durasi', 20)->nullable()->after('audio_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listening_soals', function (Blueprint $table) {
            $table->dropColumn(['audio_file', 'audio_title', 'durasi']);
        });
    }
};
