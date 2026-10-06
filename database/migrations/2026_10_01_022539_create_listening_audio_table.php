<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listening_audios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_listening_id')->constrained('modul_listenings')->cascadeOnDelete();
            $table->integer('nomor_track')->default(1);
            $table->string('judul_audio')->nullable();
            $table->string('audio_file');
            $table->string('durasi', 20)->nullable();
            $table->timestamps();
        });

        Schema::table('listening_soals', function (Blueprint $table) {
            $table->foreignId('listening_audio_id')->nullable()->after('modul_listening_id')->constrained('listening_audios')->nullOnDelete();
        });

        // Migrasikan data audio modul yang sudah ada sebelumnya ke listening_audios
        $existingModuls = DB::table('modul_listenings')
            ->whereNotNull('audio_file')
            ->where('audio_file', '!=', '')
            ->get();

        foreach ($existingModuls as $modul) {
            $audioId = DB::table('listening_audios')->insertGetId([
                'modul_listening_id' => $modul->id,
                'nomor_track' => 1,
                'judul_audio' => $modul->audio_title ?: 'Audio Utama',
                'audio_file' => $modul->audio_file,
                'durasi' => $modul->durasi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('listening_soals')
                ->where('modul_listening_id', $modul->id)
                ->whereNull('listening_audio_id')
                ->update(['listening_audio_id' => $audioId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listening_soals', function (Blueprint $table) {
            $table->dropForeign(['listening_audio_id']);
            $table->dropColumn('listening_audio_id');
        });

        Schema::dropIfExists('listening_audios');
    }
};
