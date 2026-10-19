<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class ModulListening extends Model
{
    use HasFactory;

    protected $table = 'modul_listenings';

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'tingkat_kesulitan',
        'tingkat_kelas',
        'target_rombel',
        'audio_file',
        'audio_title',
        'durasi',
        'transkrip',
        'tujuan_belajar',
        'xp_reward',
        'status',
    ];

    /**
     * Relasi ke guru pembuat modul listening.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'tujuan_belajar' => 'array',
        'xp_reward' => 'integer',
    ];

    /**
     * Relasi ke seluruh butir soal latihan listening pemahaman.
     */
    public function soals(): HasMany
    {
        return $this->hasMany(ListeningSoal::class, 'modul_listening_id')->orderBy('nomor_soal', 'asc');
    }

    /**
     * Relasi ke seluruh track audio dalam modul listening.
     */
    public function audios(): HasMany
    {
        return $this->hasMany(ListeningAudio::class, 'modul_listening_id')->orderBy('nomor_track', 'asc');
    }

    /**
     * Relasi ke riwayat pengerjaan modul listening oleh siswa.
     */
    public function progresSiswa(): HasMany
    {
        return $this->hasMany(SiswaListeningProgres::class, 'modul_listening_id');
    }

    /**
     * URL lengkap file audio listening untuk pemutar audio browser dan Flutter.
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
