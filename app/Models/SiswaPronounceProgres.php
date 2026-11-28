<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaPronounceProgres extends Model
{
    use HasFactory;

    protected $table = 'siswa_pronounce_progres';

    protected $fillable = [
        'siswa_id',
        'pronounce_word_id',
        'best_accuracy',
        'stars',
        'xp_didapat',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function word(): BelongsTo
    {
        return $this->belongsTo(PronounceWord::class, 'pronounce_word_id');
    }
}
