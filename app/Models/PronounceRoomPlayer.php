<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PronounceRoomPlayer extends Model
{
    use HasFactory;

    protected $table = 'pronounce_room_players';

    protected $fillable = [
        'room_id',
        'siswa_id',
        'total_score',
        'is_ready',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(PronounceRoom::class, 'room_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
