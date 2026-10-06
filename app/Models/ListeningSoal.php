<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ListeningSoal extends Model
{
    use HasFactory;

    protected $table = 'listening_soals';

    protected $fillable = [
        'modul_listening_id',
        'listening_audio_id',
        'nomor_soal',
        'pertanyaan',
        'audio_file',
        'audio_title',
        'durasi',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'jawaban_benar',
        'pembahasan',
    ];

    protected $casts = [
        'nomor_soal' => 'integer',
        'listening_audio_id' => 'integer',
    ];

    /**
     * Relasi ke paket modul listening induk.
     */
    public function modulListening(): BelongsTo
    {
        return $this->belongsTo(ModulListening::class, 'modul_listening_id');
    }

    /**
     * Relasi ke track audio listening yang digunakan butir soal ini.
     */
    public function listeningAudio(): BelongsTo
    {
        return $this->belongsTo(ListeningAudio::class, 'listening_audio_id');
    }

    /**
     * URL lengkap file audio rekaman soal untuk pemutar audio di Flutter siswa.
     */
    public function getAudioUrlAttribute(): ?string
    {
        if ($this->listeningAudio && $this->listeningAudio->audio_url) {
            return $this->listeningAudio->audio_url;
        }

        if (! $this->audio_file) {
            return null;
        }

        if (filter_var($this->audio_file, FILTER_VALIDATE_URL)) {
            return $this->audio_file;
        }

        return url(Storage::url($this->audio_file));
    }
}
