<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaSentenceProgres extends Model
{
    use HasFactory;

    protected $table = 'siswa_sentence_progres';

    protected $fillable = [
        'siswa_id',
        'difficulty',
        'level',
        'stars',
        'high_score',
        'is_completed',
    ];

    protected $casts = [
        'level' => 'integer',
        'stars' => 'integer',
        'high_score' => 'integer',
        'is_completed' => 'boolean',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
