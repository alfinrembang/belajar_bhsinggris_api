<?php

namespace Database\Factories;

use App\Models\Materi;
use App\Models\MateriSoal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MateriSoal>
 */
class MateriSoalFactory extends Factory
{
    protected $model = MateriSoal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'materi_id' => Materi::factory(),
            'tipe_soal' => 'pilgan',
            'pertanyaan' => fake()->sentence().'?',
            'audio_soal' => null,
            'pilihan_a' => fake()->words(3, true),
            'pilihan_b' => fake()->words(3, true),
            'pilihan_c' => fake()->words(3, true),
            'pilihan_d' => fake()->words(3, true),
            'kunci_jawaban' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'pembahasan' => fake()->sentence(),
        ];
    }

    /**
     * State untuk butir soal bertipe listening.
     */
    public function listening(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_soal' => 'listening',
            'audio_soal' => 'materi/soal_audio/sample.mp3',
        ]);
    }
}
