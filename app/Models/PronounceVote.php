<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PronounceVote extends Model
{
    use HasFactory;

    protected $table = 'pronounce_votes';

    protected $fillable = [
        'submission_id',
        'voter_siswa_id',
        'score',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(PronounceSubmission::class, 'submission_id');
    }

    public function voter(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'voter_siswa_id');
    }
}
