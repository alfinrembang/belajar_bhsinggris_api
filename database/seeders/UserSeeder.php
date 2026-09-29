<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Guru (Untuk Tenaga Pengajar)
        User::updateOrCreate(
            ['email' => 'guru@mail.com'],
            [
                'name' => 'Guru Bahasa Inggris',
                'role' => 'guru',
                'password' => Hash::make('guru123'),
            ]
        );

        // 2. Akun Admin (Untuk Manajemen Guru & Sistem)
        User::updateOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // 3. Akun Admin Placeholder (Sesuai Desain Figma: admin@gmail.com / 123456)
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Utama',
                'role' => 'admin',
                'password' => Hash::make('123456'),
            ]
        );
    }
}
