<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'kategori',
        'tingkat_kelas',
        'kode_room',
        'durasi_menit',
        'deskripsi',
        'xp_reward',
        'status',
    ];

    protected $casts = [
        'durasi_menit' => 'integer',
        'xp_reward' => 'integer',
    ];

    /**
     * Auto-generate kode room unik sebelum disimpan jika belum diisi.
     */
    protected static function booted(): void
    {
        static::creating(function (Quiz $quiz) {
            if (empty($quiz->kode_room)) {
                $quiz->kode_room = static::generateUniqueRoomCode();
            } else {
                $quiz->kode_room = strtoupper(trim($quiz->kode_room));
            }
        });
    }

    /**
     * Membuat kode room unik alfanumerik (contoh: ENG-782 atau QZ-491).
     */
    public static function generateUniqueRoomCode(): string
    {
        do {
            $prefix = fake()->randomElement(['ENG', 'QUIZ', 'QZ', 'TEST']);
            $number = rand(100, 999);
            $code = "{$prefix}-{$number}";
        } while (static::where('kode_room', $code)->exists());

        return $code;
    }

    /**
     * Relasi ke butir-butir soal kuis.
     */
    public function soals(): HasMany
    {
        return $this->hasMany(QuizSoal::class, 'quiz_id')->orderBy('nomor_soal', 'asc');
    }

    /**
     * Relasi ke riwayat pengerjaan siswa.
     */
    public function progresSiswa(): HasMany
    {
        return $this->hasMany(SiswaQuizProgres::class, 'quiz_id');
    }
}
