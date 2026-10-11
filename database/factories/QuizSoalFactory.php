<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizSoal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizSoal>
 */
class QuizSoalFactory extends Factory
{
    protected $model = QuizSoal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'nomor_soal' => 1,
            'tipe_soal' => 'pilgan',
            'reading_passage' => null,
            'audio_file' => null,
            'audio_title' => null,
            'audio_duration' => null,
            'pertanyaan' => fake()->sentence().'?',
            'pilihan_a' => fake()->words(3, true),
            'pilihan_b' => fake()->words(3, true),
            'pilihan_c' => fake()->words(3, true),
            'pilihan_d' => fake()->words(3, true),
            'kunci_jawaban' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'pembahasan' => fake()->sentence(),
        ];
    }

    /**
     * State untuk butir soal bertipe reading text.
     */
    public function reading(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_soal' => 'reading',
            'reading_passage' => fake()->paragraphs(2, true),
        ]);
    }

    /**
     * State untuk butir soal bertipe listening.
     */
    public function listening(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_soal' => 'listening',
            'audio_file' => 'quiz/audio/sample_listening.mp3',
            'audio_title' => 'Audio Percakapan Kuis',
            'audio_duration' => '01:30',
        ]);
    }
}
