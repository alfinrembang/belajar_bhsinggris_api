<?php

namespace Database\Factories;

use App\Models\PengaturanAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PengaturanAkademik>
 */
class PengaturanAkademikFactory extends Factory
{
    protected $model = PengaturanAkademik::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tahun_ajaran' => PengaturanAkademik::getTahunAjaranOtomatis(),
            'is_periode_kenaikan_aktif' => false,
            'keterangan' => 'Tahun ajaran aktif',
            'opened_at' => null,
            'closed_at' => null,
        ];
    }

    /**
     * State untuk periode kenaikan kelas aktif.
     */
    public function aktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_periode_kenaikan_aktif' => true,
            'opened_at' => now(),
            'closed_at' => null,
        ]);
    }
}
