<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'nip', 'kelas_diampu', 'role', 'password', 'api_token'])]
#[Hidden(['password', 'remember_token', 'api_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'kelas_diampu' => 'array',
        ];
    }

    /**
     * Relasi ke materi pembelajaran yang dibuat oleh guru.
     */
    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class, 'user_id');
    }

    /**
     * Relasi ke modul listening yang dibuat oleh guru.
     */
    public function modulListenings(): HasMany
    {
        return $this->hasMany(ModulListening::class, 'user_id');
    }

    /**
     * Relasi ke paket kuis yang dibuat oleh guru.
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'user_id');
    }
}
