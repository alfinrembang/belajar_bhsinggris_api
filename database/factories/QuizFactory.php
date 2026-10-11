<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quiz>
 */
class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(3),
            'kategori' => fake()->randomElement(['Grammar', 'Vocabulary', 'Reading', 'Speaking', 'General']),
            'tingkat_kelas' => fake()->randomElement(['10', '11', '12', 'Semua Kelas']),
            'kode_room' => Quiz::generateUniqueRoomCode(),
            'durasi_menit' => fake()->randomElement([10, 15, 20, 30]),
            'deskripsi' => fake()->paragraph(1),
            'xp_reward' => 60,
            'status' => 'aktif',
        ];
    }

    /**
     * State untuk kuis draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
