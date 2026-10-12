<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanAkademik extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengaturan_akademiks';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tahun_ajaran',
        'is_periode_kenaikan_aktif',
        'kkm_default',
        'durasi_kuis_default',
        'is_leaderboard_aktif',
        'keterangan',
        'opened_at',
        'closed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_periode_kenaikan_aktif' => 'boolean',
            'is_leaderboard_aktif' => 'boolean',
            'kkm_default' => 'integer',
            'durasi_kuis_default' => 'integer',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * Menghitung tahun ajaran otomatis berdasarkan kalender akademik (Juli = pergantian tahun ajaran).
     */
    public static function getTahunAjaranOtomatis(): string
    {
        $now = Carbon::now();
        $year = (int) $now->year;
        $month = (int) $now->month;

        if ($month >= 7) {
            return sprintf('%d/%d', $year, $year + 1);
        }

        return sprintf('%d/%d', $year - 1, $year);
    }

    /**
     * Mengambil konfigurasi akademik tunggal (singleton record), buat default jika belum ada.
     */
    public static function getActiveSetting(): self
    {
        $setting = self::first();

        if (! $setting) {
            $setting = self::create([
                'tahun_ajaran' => self::getTahunAjaranOtomatis(),
                'is_periode_kenaikan_aktif' => false,
                'kkm_default' => 75,
                'durasi_kuis_default' => 20,
                'is_leaderboard_aktif' => true,
                'keterangan' => 'Tahun ajaran default sistem',
            ]);
        }

        return $setting;
    }
}
