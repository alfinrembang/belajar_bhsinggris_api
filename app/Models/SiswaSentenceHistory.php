<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaSentenceHistory extends Model
{
    use HasFactory;

    protected $table = 'siswa_sentence_histories';

    protected $fillable = [
        'siswa_id',
        'sentence_soal_id',
        'is_correct',
        'score',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'score' => 'integer',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(SentenceSoal::class, 'sentence_soal_id');
    }
}
