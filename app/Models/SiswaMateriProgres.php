<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaMateriProgres extends Model
{
    protected $table = 'siswa_materi_progres';

    protected $fillable = [
        'siswa_id',
        'materi_id',
        'status',
        'skor',
        'xp_didapat',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'skor' => 'integer',
        'xp_didapat' => 'integer',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}
