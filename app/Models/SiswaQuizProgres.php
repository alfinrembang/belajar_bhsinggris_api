<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaQuizProgres extends Model
{
    use HasFactory;

    protected $table = 'siswa_quiz_progres';

    protected $fillable = [
        'siswa_id',
        'quiz_id',
        'skor',
        'jumlah_benar',
        'total_soal',
        'xp_didapat',
        'jawaban_detail',
        'waktu_pengerjaan_detik',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'skor' => 'integer',
        'jumlah_benar' => 'integer',
        'total_soal' => 'integer',
        'xp_didapat' => 'integer',
        'waktu_pengerjaan_detik' => 'integer',
        'jawaban_detail' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Relasi ke siswa yang mengerjakan.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Relasi ke paket kuis yang dikerjakan.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
