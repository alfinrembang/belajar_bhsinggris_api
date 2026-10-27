<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nis',
        'nisn',
        'nama_lengkap',
        'email',
        'email_verified_at',
        'kelas',
        'jurusan',
        'no_kelas',
        'no_absen',
        'tahun_ajaran_aktif',
        'status_kelulusan',
        'password',
        'api_token',
        'koin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Accessor untuk nama kelas lengkap (contoh: 12 RPL 1).
     */
    public function getKelasLengkapAttribute(): string
    {
        $parts = array_filter([$this->kelas, $this->jurusan, $this->no_kelas]);

        return ! empty($parts) ? implode(' ', $parts) : ($this->kelas ?? '');
    }

    /**
     * Appends untuk JSON response.
     *
     * @var list<string>
     */
    protected $appends = [
        'kelas_lengkap',
    ];

    /**
     * Relasi ke riwayat pengerjaan latihan modul materi.
     */
    public function materiProgres(): HasMany
    {
        return $this->hasMany(SiswaMateriProgres::class, 'siswa_id');
    }

    /**
     * Relasi ke riwayat pengerjaan modul listening audio.
     */
    public function listeningProgres(): HasMany
    {
        return $this->hasMany(SiswaListeningProgres::class, 'siswa_id');
    }

    /**
     * Relasi ke riwayat pengerjaan paket kuis & room ujian.
     */
    public function quizProgres(): HasMany
    {
        return $this->hasMany(SiswaQuizProgres::class, 'siswa_id');
    }

    /**
     * Relasi ke riwayat pengerjaan game puzzle kata.
     */
    public function puzzleProgres(): HasMany
    {
        return $this->hasMany(SiswaPuzzleProgres::class, 'siswa_id');
    }

    /**
     * Relasi ke riwayat pengerjaan game word fishing (pancing kata).
     */
    public function fishingProgres(): HasMany
    {
        return $this->hasMany(SiswaFishingProgres::class, 'siswa_id');
    }
}
