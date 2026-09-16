<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'password',
        'api_token',
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
}
