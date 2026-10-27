<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishingSoal extends Model
{
    use HasFactory;

    protected $table = 'fishing_soals';

    protected $fillable = [
        'level',
        'difficulty',
        'word',
        'part_of_speech',
        'meaning',
        'options',
        'explanation',
        'example_sentence',
        'pull_energy',
    ];

    protected $casts = [
        'options' => 'array',
        'level' => 'integer',
        'pull_energy' => 'integer',
    ];
}
