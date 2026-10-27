<?php

namespace Database\Factories;

use App\Models\AiImport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AiImport>
 */
class AiImportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tipe_target' => 'materi',
            'nama_file_asli' => 'sample_dokumen.docx',
            'file_path' => 'dokumen_ai/sample_dokumen.docx',
            'format_file' => 'docx',
            'ukuran_file' => 10240,
            'status' => 'pending',
            'hasil_ekstraksi_json' => null,
            'error_message' => null,
        ];
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing',
        ]);
    }

    public function completed(array $data = []): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'hasil_ekstraksi_json' => ! empty($data) ? $data : [
                'judul' => 'Simple Present Tense',
                'kategori' => 'Grammar',
                'tingkat_kelas' => 'Kelas 7',
                'deskripsi_singkat' => 'Belajar tenses dasar.',
                'penjelasan' => 'Penjelasan rumus dan contoh.',
                'contoh_teks' => 'I learn English every day.',
                'soals' => [],
            ],
        ]);
    }

    public function failed(string $error = 'Contoh pesan error ekstraksi'): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }

    public function forQuiz(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_target' => 'quiz',
        ]);
    }
}
