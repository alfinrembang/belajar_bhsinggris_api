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
        User::updateOrCreate(
            ['email' => 'adminguru@mail.com'],
            [
                'name' => 'Admin Guru',
                'role' => 'guru',
                'password' => Hash::make('adminguru123'),
            ]
        );
    }
}
