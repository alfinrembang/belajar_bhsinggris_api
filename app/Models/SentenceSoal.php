<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentenceSoal extends Model
{
    use HasFactory;

    protected $table = 'sentence_soals';

    protected $fillable = [
        'level',
        'difficulty',
        'sentence',
        'meaning',
        'tenses_category',
        'grammar_tips',
        'audio_url',
        'points',
    ];

    protected $casts = [
        'level' => 'integer',
        'points' => 'integer',
    ];

    /**
     * Memecah kalimat menjadi token kata acak untuk dimainkan siswa.
     * Mengabaikan tanda baca akhir agar bersih saat disusun.
     */
    public function getShuffledWordsAttribute(): array
    {
        $cleanSentence = trim(rtrim($this->sentence, '.!?'));
        $words = preg_split('/\s+/', $cleanSentence);
        
        $shuffled = $words;
        shuffle($shuffled);
        
        // Pastikan tidak persis sama dengan urutan awal jika kata > 1
        if (count($words) > 1 && $shuffled === $words) {
            $shuffled = array_reverse($shuffled);
        }

        return $shuffled;
    }

    /**
     * Mengambil array urutan kata yang benar.
     */
    public function getCorrectWordsAttribute(): array
    {
        $cleanSentence = trim(rtrim($this->sentence, '.!?'));
        return preg_split('/\s+/', $cleanSentence);
    }
}
