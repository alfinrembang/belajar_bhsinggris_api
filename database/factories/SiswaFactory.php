<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => fake()->unique()->numerify('######'),
            'nisn' => fake()->unique()->numerify('##########'),
            'nama_lengkap' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'kelas' => fake()->randomElement(['10', '11', '12']),
            'jurusan' => fake()->randomElement(['RPL', 'DKV', 'TSM', 'BD', 'SA', 'MPLB', 'TKKR']),
            'no_kelas' => fake()->randomElement(['1', '2', '3']),
            'no_absen' => (string) fake()->numberBetween(1, 36),
            'tahun_ajaran_aktif' => '2026/2027',
            'status_kelulusan' => 'aktif',
            'password' => Hash::make('12345678'),
            'api_token' => null,
        ];
    }
}
