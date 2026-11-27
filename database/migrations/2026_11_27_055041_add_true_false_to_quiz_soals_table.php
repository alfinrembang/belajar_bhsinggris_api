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
        Schema::table('quiz_soals', function (Blueprint $table) {
            $table->string('tipe_soal', 30)->default('pilgan')->change();
            $table->json('pernyataan_items')->nullable()->after('pertanyaan');
            $table->text('pilihan_a')->nullable()->change();
            $table->text('pilihan_b')->nullable()->change();
            $table->text('pilihan_c')->nullable()->change();
            $table->text('pilihan_d')->nullable()->change();
            $table->string('kunci_jawaban', 255)->nullable()->default('A')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_soals', function (Blueprint $table) {
            $table->dropColumn('pernyataan_items');
            $table->enum('tipe_soal', ['pilgan', 'reading', 'listening'])->default('pilgan')->change();
            $table->text('pilihan_a')->nullable(false)->change();
            $table->text('pilihan_b')->nullable(false)->change();
            $table->text('pilihan_c')->nullable(false)->change();
            $table->text('pilihan_d')->nullable(false)->change();
            $table->char('kunci_jawaban', 2)->default('A')->change();
        });
    }
};
