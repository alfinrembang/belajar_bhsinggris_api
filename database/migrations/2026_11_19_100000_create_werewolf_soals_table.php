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
        if (! Schema::hasTable('werewolf_soals')) {
            Schema::create('werewolf_soals', function (Blueprint $table) {
                $table->id();
                $table->string('theme', 100)->default('Umum'); // Kategori / Tema
                $table->string('domba_word', 100); // e.g. "FOREST"
                $table->string('domba_meaning', 255); // e.g. "Hutan Lebat"
                $table->json('domba_clues')->nullable(); // Petunjuk kata Domba (JSON array)
                $table->string('serigala_word', 100); // e.g. "JUNGLE"
                $table->string('serigala_meaning', 255); // e.g. "Rimba Liar"
                $table->json('serigala_clues')->nullable(); // Petunjuk kata Serigala (JSON array)
                $table->text('english_difference')->nullable(); // Penjelasan edukasi perbedaan kata
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['domba_word', 'serigala_word']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('werewolf_soals');
    }
};
