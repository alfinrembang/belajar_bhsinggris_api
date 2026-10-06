<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListeningAudio extends Model
{
    use HasFactory;

    protected $table = 'listening_audios';

    protected $fillable = [
        'modul_listening_id',
        'nomor_track',
        'judul_audio',
        'audio_file',
        'durasi',
    ];

    protected $appends = ['audio_url'];

    /**
     * URL publik untuk memutar audio track ini.
     */
    public function getAudioUrlAttribute(): ?string
    {
        if (! $this->audio_file) {
            return null;
        }

        if (str_starts_with($this->audio_file, 'http://') || str_starts_with($this->audio_file, 'https://')) {
            return $this->audio_file;
        }

        return asset('storage/'.$this->audio_file);
    }

    public function modul(): BelongsTo
    {
        return $this->belongsTo(ModulListening::class, 'modul_listening_id');
    }

    public function soals(): HasMany
    {
        return $this->hasMany(ListeningSoal::class, 'listening_audio_id');
    }
}
