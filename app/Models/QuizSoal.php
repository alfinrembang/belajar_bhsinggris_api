<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class QuizSoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'nomor_soal',
        'tipe_soal',
        'reading_passage',
        'audio_file',
        'audio_title',
        'audio_duration',
        'pertanyaan',
        'pernyataan_items',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'kunci_jawaban',
        'pembahasan',
    ];

    protected $casts = [
        'nomor_soal' => 'integer',
        'pernyataan_items' => 'array',
    ];

    /**
     * Relasi ke paket kuis induk.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * URL lengkap file audio untuk soal tipe listening.
     */
    public function getAudioUrlAttribute(): ?string
    {
        if (! $this->audio_file) {
            return null;
        }

        if (filter_var($this->audio_file, FILTER_VALIDATE_URL)) {
            return $this->audio_file;
        }

        return url(Storage::url($this->audio_file));
    }

    /**
     * Menentukan apakah soal bertipe tabel pernyataan True / False.
     */
    public function isTrueFalse(): bool
    {
        return $this->tipe_soal === 'true_false';
    }

    /**
     * Menormalisasi input nilai True/False menjadi 'true', 'false', atau null.
     */
    public static function normalizeTrueFalseValue(mixed $value): ?string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_int($value)) {
            if ($value === 1) {
                return 'true';
            }
            if ($value === 0) {
                return 'false';
            }
        }

        if (! is_string($value) && ! is_numeric($value)) {
            return null;
        }

        $normalized = strtolower(trim((string) $value));

        if (in_array($normalized, ['true', 'benar', 't', '1'], true)) {
            return 'true';
        }

        if (in_array($normalized, ['false', 'salah', 'f', '0'], true)) {
            return 'false';
        }

        return null;
    }

    /**
     * Memeriksa apakah butir soal telah dijawab lengkap oleh siswa.
     */
    public function hasCompleteAnswer(mixed $rawAnswer): bool
    {
        if (! $this->isTrueFalse()) {
            return $rawAnswer !== null && trim((string) $rawAnswer) !== '';
        }

        $items = $this->pernyataan_items ?? [];
        if (empty($items)) {
            return false;
        }

        $answers = $this->extractTrueFalseAnswers($rawAnswer);
        foreach ($items as $idx => $item) {
            $no = $item['no'] ?? ($idx + 1);
            $val = $answers[$idx] ?? $answers[(string) $idx] ?? $answers[(string) $no] ?? null;
            if (static::normalizeTrueFalseValue($val) === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * Mengevaluasi jawaban siswa terhadap kunci jawaban (mendukung pilgan maupun tabel True/False).
     *
     * @return array{
     *     is_benar: bool,
     *     jawaban_siswa: string,
     *     kunci_jawaban: string,
     *     pernyataan_review: list<array{no: int, teks: string, jawaban_siswa: string, kunci_jawaban: string, is_benar: bool}>
     * }
     */
    public function evaluateAnswer(mixed $rawAnswer): array
    {
        if (! $this->isTrueFalse()) {
            $userAns = strtoupper(trim((string) ($rawAnswer ?? '')));
            $kunci = strtoupper(trim((string) $this->kunci_jawaban));

            return [
                'is_benar' => $userAns !== '' && $userAns === $kunci,
                'jawaban_siswa' => $userAns,
                'kunci_jawaban' => $kunci,
                'pernyataan_review' => [],
            ];
        }

        $items = $this->pernyataan_items ?? [];
        $answers = $this->extractTrueFalseAnswers($rawAnswer);

        $allCorrect = ! empty($items);
        $userParts = [];
        $keyParts = [];
        $review = [];

        foreach ($items as $idx => $item) {
            $no = (int) ($item['no'] ?? ($idx + 1));
            $teks = (string) ($item['teks'] ?? '');
            $expected = static::normalizeTrueFalseValue($item['kunci'] ?? 'true') ?? 'true';
            $givenRaw = $answers[$idx] ?? $answers[(string) $idx] ?? $answers[(string) $no] ?? null;
            $given = static::normalizeTrueFalseValue($givenRaw);

            $rowCorrect = $given !== null && $given === $expected;
            if (! $rowCorrect) {
                $allCorrect = false;
            }

            $givenLabel = $given !== null ? strtoupper($given) : '-';
            $expectedLabel = strtoupper($expected);

            $userParts[] = $givenLabel;
            $keyParts[] = $expectedLabel;
            $review[] = [
                'no' => $no,
                'teks' => $teks,
                'jawaban_siswa' => $givenLabel,
                'kunci_jawaban' => $expectedLabel,
                'is_benar' => $rowCorrect,
            ];
        }

        return [
            'is_benar' => $allCorrect,
            'jawaban_siswa' => implode(', ', $userParts),
            'kunci_jawaban' => implode(', ', $keyParts),
            'pernyataan_review' => $review,
        ];
    }

    /**
     * Mengambil peta jawaban pernyataan True/False dari payload request API.
     *
     * @return array<int|string, mixed>
     */
    private function extractTrueFalseAnswers(mixed $rawAnswer): array
    {
        if (is_array($rawAnswer)) {
            return $rawAnswer;
        }

        if (is_string($rawAnswer)) {
            $decoded = json_decode($rawAnswer, true);
            if (is_array($decoded)) {
                return $decoded;
            }

            if (str_contains($rawAnswer, ',')) {
                return array_map('trim', explode(',', $rawAnswer));
            }
        }

        return [];
    }
}
