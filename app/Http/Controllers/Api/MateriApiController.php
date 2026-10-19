<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Siswa;
use App\Models\SiswaMateriProgres;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriApiController extends Controller
{
    /**
     * Mengambil daftar materi aktif untuk aplikasi Flutter siswa.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Materi::where('status', 'aktif')
            ->with(['user', 'soals'])
            ->withCount('soals')
            ->latest();

        $siswaId = $request->input('siswa_id');
        $siswa = $siswaId ? Siswa::find($siswaId) : null;
        $rombel = $siswa ? $siswa->kelas_lengkap : null;
        $tingkat = $siswa ? (string) $siswa->kelas : null;

        if (! $rombel && $request->filled('kelas')) {
            $rawKelas = trim((string) $request->kelas);
            if (preg_match('/\b(10|11|12)\b/', $rawKelas, $matches)) {
                $tingkat = $matches[1];
            } elseif (preg_match('/\b(XII|XI|X)\b/i', $rawKelas, $matches)) {
                $romanMap = ['X' => '10', 'XI' => '11', 'XII' => '12'];
                $tingkat = $romanMap[strtoupper($matches[1])] ?? null;
            }

            if (preg_match('/^(10|11|12)\s+[A-Za-z0-9]+\s+\d+$/', $rawKelas)) {
                $rombel = $rawKelas;
            }
        }

        // 1. Filter Tingkat Kelas (10, 11, 12, atau Semua Kelas)
        if ($tingkat || $request->filled('kelas')) {
            $rawKelas = (string) ($request->kelas ?? $tingkat);
            $query->where(function ($q) use ($rawKelas, $tingkat, $rombel) {
                $q->where('tingkat_kelas', 'Semua Kelas')
                    ->orWhere('tingkat_kelas', $rawKelas);
                if ($tingkat) {
                    $q->orWhere('tingkat_kelas', $tingkat);
                }
                if ($rombel) {
                    $q->orWhere('tingkat_kelas', $rombel);
                }
            });
        }

        // 2. Isolasi Konten: Guru Pengampu Kelas Siswa
        if ($rombel) {
            $teacherIds = User::where('role', 'guru')
                ->whereJsonContains('kelas_diampu', $rombel)
                ->pluck('id');

            if ($teacherIds->isNotEmpty()) {
                $query->where(function ($q) use ($teacherIds, $rombel) {
                    $q->where(function ($sub) use ($teacherIds, $rombel) {
                        $sub->whereIn('user_id', $teacherIds)
                            ->where(function ($r) use ($rombel) {
                                $r->whereNull('target_rombel')
                                    ->orWhere('target_rombel', $rombel);
                            });
                    })->orWhere(function ($sub) {
                        $sub->whereNull('user_id')
                            ->orWhereHas('user', fn ($u) => $u->where('role', 'admin'));
                    });
                });
            } else {
                // Jika belum ada guru yang diplot ke rombel ini, sediakan materi umum sekolah
                $query->where(function ($q) {
                    $q->whereNull('user_id')
                        ->orWhereHas('user', fn ($u) => $u->where('role', 'admin'))
                        ->orWhereNull('target_rombel');
                });
            }
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori') && ! in_array(strtolower($request->kategori), ['all', 'semua', ''])) {
            $query->where('kategori', $request->kategori);
        }

        $siswaId = $request->input('siswa_id');
        $completedMap = [];
        if ($siswaId) {
            $completedMap = SiswaMateriProgres::where('siswa_id', $siswaId)
                ->where('status', 'selesai')
                ->pluck('completed_at', 'materi_id')
                ->toArray();
        }

        $materis = $query->get()->map(function ($materi) use ($completedMap) {
            $isCompleted = array_key_exists($materi->id, $completedMap);

            return [
                'id' => $materi->id,
                'judul' => $materi->judul,
                'kategori' => $materi->kategori,
                'tingkat_kelas' => $materi->tingkat_kelas,
                'deskripsi_singkat' => $materi->deskripsi_singkat,
                'gambar_url' => $materi->gambar_url,
                'audio_url' => $materi->audio_url,
                'xp_reward' => $materi->xp_reward,
                'jumlah_soal' => $materi->soals_count,
                'is_completed' => $isCompleted,
                'completed_at' => $isCompleted ? ($completedMap[$materi->id] ? date('d M Y H:i', strtotime($completedMap[$materi->id])) : null) : null,
                'created_at' => $materi->created_at?->format('d M Y'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar materi berhasil dimuat',
            'data' => $materis,
        ]);
    }

    /**
     * Mengambil detail materi lengkap beserta latihan soal untuk siswa.
     */
    public function show(int $id): JsonResponse
    {
        $materi = Materi::with('soals')->where('status', 'aktif')->find($id);

        if (! $materi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Modul materi tidak ditemukan atau belum aktif!',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail materi berhasil dimuat',
            'data' => [
                'id' => $materi->id,
                'judul' => $materi->judul,
                'kategori' => $materi->kategori,
                'tingkat_kelas' => $materi->tingkat_kelas,
                'deskripsi_singkat' => $materi->deskripsi_singkat,
                'penjelasan' => $materi->penjelasan,
                'contoh_teks' => $materi->contoh_teks,
                'gambar_url' => $materi->gambar_url,
                'audio_url' => $materi->audio_url,
                'xp_reward' => $materi->xp_reward,
                'soals' => $materi->soals->map(function ($soal) {
                    return [
                        'id' => $soal->id,
                        'tipe_soal' => $soal->tipe_soal,
                        'pertanyaan' => $soal->pertanyaan,
                        'audio_soal_url' => $soal->audio_soal_url,
                        'pilihan' => [
                            'A' => $soal->pilihan_a,
                            'B' => $soal->pilihan_b,
                            'C' => $soal->pilihan_c,
                            'D' => $soal->pilihan_d,
                        ],
                        'kunci_jawaban' => $soal->kunci_jawaban,
                        'pembahasan' => $soal->pembahasan,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Menyimpan status penyelesaian modul materi oleh siswa dengan kalkulasi skor & anti-cheat XP di sisi server.
     */
    public function selesaikan(Request $request, int $id): JsonResponse
    {
        $materi = Materi::with('soals')->where('status', 'aktif')->find($id);

        if (! $materi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Modul materi tidak ditemukan atau belum aktif!',
            ], 404);
        }

        $request->validate([
            'siswa_id' => 'nullable|integer',
            'skor' => 'nullable|numeric|min:0|max:100',
            'jawaban' => 'nullable|array',
            'jawaban.*' => 'nullable|string|max:10',
        ]);

        $siswaId = $request->input('siswa_id');
        $validSiswa = $siswaId ? Siswa::find($siswaId) : null;
        $effectiveSiswaId = $validSiswa?->id;

        // Anti-Cheat: Jika materi memiliki latihan soal, seluruh butir soal wajib dikerjakan
        $totalSoal = $materi->soals->count();
        if ($totalSoal > 0) {
            $userAnswers = $request->input('jawaban');
            $answeredCount = is_array($userAnswers)
                ? count(array_filter($userAnswers, fn ($v) => $v !== null && trim((string) $v) !== ''))
                : 0;

            if ($answeredCount < $totalSoal) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Seluruh {$totalSoal} butir latihan soal pemahaman wajib diselesaikan sebelum menuntaskan materi!",
                ], 422);
            }

            $benar = 0;
            foreach ($materi->soals as $soal) {
                $userAns = strtoupper(trim((string) ($userAnswers[$soal->id] ?? $userAnswers[(string) $soal->id] ?? '')));
                if ($userAns !== '' && $userAns === strtoupper(trim((string) $soal->kunci_jawaban))) {
                    $benar++;
                }
            }
            $skor = (int) round(($benar / $totalSoal) * 100);
        } else {
            // Modul materi tanpa butir soal (hanya materi bacaan/cerita), otomatis tuntas sempurna
            $skor = 100;
        }

        // Anti-Cheat: XP reward SELALU ditentukan dari nilai di database modul materi, bukan payload request
        $baseXp = (int) ($materi->xp_reward ?? 50);
        $xpDidapat = $totalSoal > 0 ? (int) round($baseXp * max(0.2, $skor / 100)) : $baseXp;
        $xpDidapat = max(5, $xpDidapat);

        if ($effectiveSiswaId) {
            DB::transaction(function () use ($effectiveSiswaId, $materi, $skor, $xpDidapat) {
                SiswaMateriProgres::updateOrCreate(
                    ['siswa_id' => $effectiveSiswaId, 'materi_id' => $materi->id],
                    [
                        'status' => 'selesai',
                        'skor' => $skor,
                        'xp_didapat' => $xpDidapat,
                        'completed_at' => now(),
                    ]
                );
            });
        }

        $totalMateri = Materi::where('status', 'aktif')->count();
        $materiSelesai = $effectiveSiswaId
            ? SiswaMateriProgres::where('siswa_id', $effectiveSiswaId)->where('status', 'selesai')->count()
            : 1;

        $persentase = $totalMateri > 0 ? (int) round(($materiSelesai / $totalMateri) * 100) : 0;

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat! Modul materi berhasil diselesaikan.',
            'data' => [
                'materi_id' => $materi->id,
                'judul' => $materi->judul,
                'skor' => $skor,
                'xp_didapat' => $xpDidapat,
                'total_materi' => $totalMateri,
                'materi_selesai' => $materiSelesai,
                'persentase' => min($persentase, 100),
            ],
        ]);
    }

    /**
     * Mengambil ringkasan progres belajar materi untuk siswa.
     */
    public function progres(Request $request): JsonResponse
    {
        $siswaId = $request->input('siswa_id');
        $totalMateri = Materi::where('status', 'aktif')->count();

        $completedQuery = SiswaMateriProgres::where('status', 'selesai')
            ->with('materi')
            ->latest('completed_at');

        if ($siswaId) {
            $completedQuery->where('siswa_id', $siswaId);
        }

        $completedList = $completedQuery->get();
        $materiSelesai = $completedList->count();
        $persentase = $totalMateri > 0 ? (int) round(($materiSelesai / $totalMateri) * 100) : 0;

        $materiTerakhir = $completedList->first()?->materi;

        // Ambil materi rekomendasi selanjutnya (materi aktif yang belum selesai)
        $completedIds = $completedList->pluck('materi_id')->toArray();
        $materiLanjut = Materi::where('status', 'aktif')
            ->whereNotIn('id', $completedIds)
            ->first() ?? $materiTerakhir ?? Materi::where('status', 'aktif')->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_materi' => $totalMateri,
                'materi_selesai' => $materiSelesai,
                'persentase' => min($persentase, 100),
                'completed_materi_ids' => $completedIds,
                'materi_terakhir' => $materiTerakhir ? [
                    'id' => $materiTerakhir->id,
                    'judul' => $materiTerakhir->judul,
                    'kategori' => $materiTerakhir->kategori,
                    'deskripsi_singkat' => $materiTerakhir->deskripsi_singkat,
                ] : null,
                'materi_lanjutkan' => $materiLanjut ? [
                    'id' => $materiLanjut->id,
                    'judul' => $materiLanjut->judul,
                    'kategori' => $materiLanjut->kategori,
                    'deskripsi_singkat' => $materiLanjut->deskripsi_singkat,
                ] : null,
            ],
        ]);
    }
}
