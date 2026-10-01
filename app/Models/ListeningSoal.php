<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListeningSoal extends Model
{
    use HasFactory;

    protected $table = 'listening_soals';

    protected $fillable = [
        'modul_listening_id',
        'nomor_soal',
        'pertanyaan',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'jawaban_benar',
        'pembahasan',
    ];

    protected $casts = [
        'nomor_soal' => 'integer',
    ];

    /**
     * Relasi ke paket modul listening induk.
     */
    public function modulListening(): BelongsTo
    {
        return $this->belongsTo(ModulListening::class, 'modul_listening_id');
    }
}
