<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaListeningProgres extends Model
{
    use HasFactory;

    protected $table = 'siswa_listening_progres';

    protected $fillable = [
        'siswa_id',
        'modul_listening_id',
        'status',
        'skor',
        'xp_didapat',
        'completed_at',
    ];

    protected $casts = [
        'skor' => 'integer',
        'xp_didapat' => 'integer',
        'completed_at' => 'datetime',
    ];

    /**
     * Relasi ke siswa yang mengerjakan modul.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Relasi ke modul listening yang diselesaikan.
     */
    public function modulListening(): BelongsTo
    {
        return $this->belongsTo(ModulListening::class, 'modul_listening_id');
    }
}
