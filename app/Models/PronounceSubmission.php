<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PronounceSubmission extends Model
{
    use HasFactory;

    protected $table = 'pronounce_submissions';

    protected $fillable = [
        'room_id',
        'round',
        'siswa_id',
        'word_id',
        'audio_path',
        'audio_url',
        'ai_accuracy',
        'average_score',
        'total_voters',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(PronounceRoom::class, 'room_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function word(): BelongsTo
    {
        return $this->belongsTo(PronounceWord::class, 'word_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PronounceVote::class, 'submission_id');
    }
}
