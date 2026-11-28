<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PronounceRoom extends Model
{
    use HasFactory;

    protected $table = 'pronounce_rooms';

    protected $fillable = [
        'code',
        'host_id',
        'title',
        'status',
        'max_players',
        'current_turn_siswa_id',
        'current_round',
        'total_rounds',
        'current_word_id',
    ];

    public function host(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'host_id');
    }

    public function currentTurnSiswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'current_turn_siswa_id');
    }

    public function currentWord(): BelongsTo
    {
        return $this->belongsTo(PronounceWord::class, 'current_word_id');
    }

    public function players(): HasMany
    {
        return $this->hasMany(PronounceRoomPlayer::class, 'room_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(PronounceSubmission::class, 'room_id');
    }
}
