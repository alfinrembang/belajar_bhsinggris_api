<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PronounceWord extends Model
{
    use HasFactory;

    protected $table = 'pronounce_words';

    protected $fillable = [
        'word',
        'phonetic',
        'part_of_speech',
        'meaning',
        'difficulty',
        'audio_url',
        'tips',
        'example_sentence',
    ];
}
