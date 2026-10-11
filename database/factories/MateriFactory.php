<?php

namespace Database\Factories;

use App\Models\Materi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Materi>
 */
class MateriFactory extends Factory
{
    protected $model = Materi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(3),
            'kategori' => fake()->randomElement(['Grammar', 'Vocabulary', 'Listening', 'Reading']),
            'tingkat_kelas' => fake()->randomElement(['10', '11', '12', 'Semua Kelas']),
            'deskripsi_singkat' => fake()->paragraph(1),
            'penjelasan' => fake()->paragraphs(3, true),
            'contoh_teks' => fake()->paragraph(2),
            'gambar_materi' => null,
            'audio_materi' => null,
            'xp_reward' => 50,
            'status' => 'aktif',
        ];
    }

    /**
     * State untuk materi berstatus draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
