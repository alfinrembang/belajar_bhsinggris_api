<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaPuzzleProgres extends Model
{
    use HasFactory;

    protected $table = 'siswa_puzzle_progres';

    protected $fillable = [
        'siswa_id',
        'difficulty',
        'level',
        'stars',
        'is_completed',
        'best_time',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'level' => 'integer',
        'stars' => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
