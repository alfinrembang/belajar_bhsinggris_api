<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nis',
        'nisn',
        'nama_lengkap',
        'email',
        'email_verified_at',
        'kelas',
        'jurusan',
        'no_kelas',
        'no_absen',
        'tahun_ajaran_aktif',
        'status_kelulusan',
        'password',
        'api_token',
        'koin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Accessor untuk nama kelas lengkap (contoh: 12 RPL 1).
     */
    public function getKelasLengkapAttribute(): string
    {
        $parts = array_filter([$this->kelas, $this->jurusan, $this->no_kelas]);

        return ! empty($parts) ? implode(' ', $parts) : ($this->kelas ?? '');
    }

    /**
     * Appends untuk JSON response.
     *
     * @var list<string>
     */
    protected $appends = [
        'kelas_lengkap',
    ];

    /**
     * Relasi ke riwayat pengerjaan latihan modul materi.
     */
    public function materiProgres(): HasMany
    {
        return $this->hasMany(SiswaMateriProgres::class, 'siswa_id');
    }

    /**
     * Relasi ke riwayat pengerjaan modul listening audio.
     */
    public function listeningProgres(): HasMany
    {
        return $this->hasMany(SiswaListeningProgres::class, 'siswa_id');
    }

    /**
     * Relasi ke riwayat pengerjaan paket kuis & room ujian.
     */
    public function quizProgres(): HasMany
    {
        return $this->hasMany(SiswaQuizProgres::class, 'siswa_id');
    }

    /**
     * Relasi ke riwayat pengerjaan game word fishing (pancing kata).
     */
    public function fishingProgres(): HasMany
    {
        return $this->hasMany(SiswaFishingProgres::class, 'siswa_id');
    }

    /**
     * Mengambil daftar tingkat kelas unik yang ada siswanya di database (misal: ['10', '11', '12']).
     *
     * @return Collection<int, string>
     */
    public static function getDaftarKelas(): Collection
    {
        $kelas = static::whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->orderByRaw('CAST(kelas AS UNSIGNED) ASC, kelas ASC')
            ->pluck('kelas');

        return $kelas->isNotEmpty() ? $kelas : collect(['10', '11', '12']);
    }

    /**
     * Mengambil daftar jurusan unik yang ada siswanya di database (misal: ['RPL', 'TSM']).
     *
     * @return Collection<int, string>
     */
    public static function getDaftarJurusan(): Collection
    {
        $jurusan = static::whereNotNull('jurusan')
            ->where('jurusan', '!=', '')
            ->distinct()
            ->orderBy('jurusan', 'asc')
            ->pluck('jurusan');

        return $jurusan->isNotEmpty() ? $jurusan : collect(['RPL', 'DKV', 'TSM', 'BD', 'SA', 'MPLB', 'TKKR']);
    }

    /**
     * Mengambil seluruh daftar rombel kelas lengkap yang ada siswanya di database.
     *
     * @return Collection<int, string>
     */
    public static function getDaftarRombel(): Collection
    {
        return static::select('kelas', 'jurusan', 'no_kelas')
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->get()
            ->map->kelas_lengkap
            ->filter()
            ->unique()
            ->values();
    }

    /**
     * Mengambil daftar rombel terkelompok per tingkat kelas untuk dropdown ber-optgroup.
     * Contoh hasil:
     * [
     *     '10' => ['10 RPL 1', '10 TSM 1', '10 TSM 2'],
     *     '11' => ['11 RPL 1'],
     * ]
     *
     * @return array<string, list<string>>
     */
    public static function getDaftarRombelGrouped(): array
    {
        $rombels = static::select('kelas', 'jurusan', 'no_kelas')
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->get();

        $grouped = [];
        foreach ($rombels as $item) {
            $kelasKey = (string) $item->kelas;
            $label = $item->kelas_lengkap;
            if (! empty($label)) {
                $grouped[$kelasKey][] = $label;
            }
        }

        // Urutkan key tingkat kelas (10, 11, 12, dst.)
        uksort($grouped, function ($a, $b) {
            return (int) $a <=> (int) $b;
        });

        foreach ($grouped as &$list) {
            $list = array_values(array_unique($list));
            sort($list);
        }

        return $grouped;
    }

    /**
     * Mengekstrak konteks kelas, tingkat, dan rombel siswa untuk penyaringan konten API mobile.
     *
     * @return array{siswa: ?self, rawKelas: ?string, tingkat: ?string, rombel: ?string}
     */
    public static function resolveApiClassContext(mixed $siswaId, ?string $kelasParam): array
    {
        $siswa = $siswaId ? static::find($siswaId) : null;
        $rombel = $siswa ? ($siswa->kelas_lengkap ?: null) : null;
        $tingkat = $siswa && $siswa->kelas ? (string) $siswa->kelas : null;
        $rawKelas = $kelasParam !== null && trim($kelasParam) !== '' ? trim($kelasParam) : $tingkat;

        if (! $rombel && $kelasParam !== null && trim($kelasParam) !== '') {
            $trimmed = trim($kelasParam);
            if (preg_match('/\b(10|11|12)\b/', $trimmed, $matches)) {
                $tingkat = $matches[1];
            } elseif (preg_match('/\b(XII|XI|X)\b/i', $trimmed, $matches)) {
                $romanMap = ['X' => '10', 'XI' => '11', 'XII' => '12'];
                $tingkat = $romanMap[strtoupper($matches[1])] ?? null;
            }

            if (preg_match('/^(10|11|12)\s+[A-Za-z0-9]+(?:\s+\d+)?$/', $trimmed)) {
                $rombel = $trimmed;
            }
        }

        return [
            'siswa' => $siswa,
            'rawKelas' => $rawKelas,
            'tingkat' => $tingkat,
            'rombel' => $rombel,
        ];
    }
}
