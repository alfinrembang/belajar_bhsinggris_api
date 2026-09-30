<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'kategori',
        'tingkat_kelas',
        'deskripsi_singkat',
        'penjelasan',
        'contoh_teks',
        'gambar_materi',
        'audio_materi',
        'xp_reward',
        'status',
    ];

    /**
     * Relasi ke butir latihan soal pemahaman.
     */
    public function soals(): HasMany
    {
        return $this->hasMany(MateriSoal::class, 'materi_id');
    }

    /**
     * URL lengkap file audio materi untuk browser & Flutter.
     */
    public function getAudioUrlAttribute(): ?string
    {
        if (! $this->audio_materi) {
            return null;
        }

        if (filter_var($this->audio_materi, FILTER_VALIDATE_URL)) {
            return $this->audio_materi;
        }

        return url(Storage::url($this->audio_materi));
    }

    /**
     * URL lengkap file gambar cover/ilustrasi materi.
     */
    public function getGambarUrlAttribute(): ?string
    {
        if (! $this->gambar_materi) {
            return null;
        }

        if (filter_var($this->gambar_materi, FILTER_VALIDATE_URL)) {
            return $this->gambar_materi;
        }

        return url(Storage::url($this->gambar_materi));
    }
}
