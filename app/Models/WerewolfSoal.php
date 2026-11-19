<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WerewolfSoal extends Model
{
    use HasFactory;

    protected $table = 'werewolf_soals';

    protected $fillable = [
        'theme',
        'domba_word',
        'domba_meaning',
        'domba_clues',
        'serigala_word',
        'serigala_meaning',
        'serigala_clues',
        'english_difference',
        'is_active',
    ];

    protected $casts = [
        'domba_clues' => 'array',
        'serigala_clues' => 'array',
        'is_active' => 'boolean',
    ];
}
