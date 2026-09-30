<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MateriSoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'materi_id',
        'tipe_soal',
        'pertanyaan',
        'audio_soal',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'kunci_jawaban',
        'pembahasan',
    ];

    /**
     * Relasi balik ke induk Materi.
     */
    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    /**
     * URL lengkap file audio untuk soal tipe listening.
     */
    public function getAudioSoalUrlAttribute(): ?string
    {
        if (! $this->audio_soal) {
            return null;
        }

        if (filter_var($this->audio_soal, FILTER_VALIDATE_URL)) {
            return $this->audio_soal;
        }

        return url(Storage::url($this->audio_soal));
    }
}
