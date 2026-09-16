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
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('nis')->nullable()->change();
            $table->string('nisn')->nullable()->unique()->after('nis');
            $table->string('jurusan')->nullable()->after('kelas');
            $table->string('no_kelas')->nullable()->after('jurusan');
            $table->string('no_absen')->nullable()->change();
            $table->string('password')->nullable()->after('no_absen');
            $table->string('api_token', 80)->unique()->nullable()->after('password');
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn([
                'nisn',
                'jurusan',
                'no_kelas',
                'password',
                'api_token',
                'email_verified_at',
            ]);
        });
    }
};
