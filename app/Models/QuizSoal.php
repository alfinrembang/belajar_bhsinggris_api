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
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'kunci_jawaban',
        'pembahasan',
    ];

    protected $casts = [
        'nomor_soal' => 'integer',
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
}
