<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaFishingProgres extends Model
{
    use HasFactory;

    protected $table = 'siswa_fishing_progres';

    protected $fillable = [
        'siswa_id',
        'difficulty',
        'level',
        'stars',
        'high_score',
        'fish_name',
        'fish_emoji',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'level' => 'integer',
        'stars' => 'integer',
        'high_score' => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
