<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SelesaikanListeningRequest;
use App\Models\ModulListening;
use App\Models\Siswa;
use App\Models\SiswaListeningProgres;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModulListeningApiController extends Controller
{
    /**
     * Mengambil daftar track audio listening aktif untuk aplikasi Flutter siswa.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ModulListening::where('status', 'aktif')
            ->with(['user', 'soals'])
            ->withCount('soals')
            ->latest('id');

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
                $query->where(function ($q) {
                    $q->whereNull('user_id')
                        ->orWhereHas('user', fn ($u) => $u->where('role', 'admin'))
                        ->orWhereNull('target_rombel');
                });
            }
        }

        // Filter Tingkat Kesulitan
        if ($request->filled('tingkat') && ! in_array(strtolower($request->tingkat), ['all', 'semua', ''])) {
            $query->where('tingkat_kesulitan', ucfirst(strtolower($request->tingkat)));
        }

        $siswaId = $request->input('siswa_id');
        $completedMap = [];
        if ($siswaId) {
            $completedMap = SiswaListeningProgres::where('siswa_id', $siswaId)
                ->where('status', 'selesai')
                ->pluck('completed_at', 'modul_listening_id')
                ->toArray();
        }

        $listenings = $query->get()->map(function ($item) use ($completedMap) {
            $isCompleted = array_key_exists($item->id, $completedMap);

            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'deskripsi' => $item->deskripsi,
                'tingkat_kesulitan' => $item->tingkat_kesulitan,
                'tingkat_kelas' => $item->tingkat_kelas,
                'audio_title' => $item->audio_title ?? "Audio Track #{$item->id}",
                'durasi' => $item->durasi ?? '02:00',
                'audio_url' => $item->audio_url,
                'xp_reward' => $item->xp_reward,
                'jumlah_soal' => $item->soals_count,
                'is_completed' => $isCompleted,
                'completed_at' => $isCompleted ? ($completedMap[$item->id] ? date('d M Y H:i', strtotime($completedMap[$item->id])) : null) : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar modul listening berhasil dimuat',
            'data' => $listenings,
        ]);
    }

    /**
     * Mengambil detail satu paket listening beserta daftar audio track dan butir latihan soal.
     */
    public function show(int $id): JsonResponse
    {
        $listening = ModulListening::with(['audios', 'soals.listeningAudio'])->where('status', 'aktif')->find($id);

        if (! $listening) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paket modul listening tidak ditemukan atau belum aktif!',
            ], 404);
        }

        // Hitung kelompok soal untuk setiap audio track secara akurat (tahan jika nomor loncat)
        $audioQuestionsMap = [];
        foreach ($listening->soals as $s) {
            $audioKey = $s->listening_audio_id ?: 0;
            $audioQuestionsMap[$audioKey][] = $s->nomor_soal;
        }

        $rentangMap = [];
        foreach ($audioQuestionsMap as $audioKey => $nomors) {
            sort($nomors);
            $nomors = array_values(array_unique($nomors));
            $ranges = [];
            $start = $nomors[0];
            $prev = $start;

            for ($i = 1; $i < count($nomors); $i++) {
                $curr = $nomors[$i];
                if ($curr === $prev + 1) {
                    $prev = $curr;
                } else {
                    $ranges[] = ($start === $prev) ? "{$start}" : "{$start}-{$prev}";
                    $start = $curr;
                    $prev = $curr;
                }
            }
            $ranges[] = ($start === $prev) ? "{$start}" : "{$start}-{$prev}";

            $rentangMap[$audioKey] = 'Soal No. '.implode(', ', $ranges);
        }

        $prevAudioUrl = null;
        $soals = $listening->soals->map(function ($s, $idx) use ($listening, $rentangMap, &$prevAudioUrl) {
            $audioUrl = $s->audio_url ?: $listening->audio_url;
            $audioNomor = $s->listeningAudio?->nomor_track ?? 1;
            $audioKey = $s->listening_audio_id ?: 0;
            $rentang = $rentangMap[$audioKey] ?? "Soal No. {$s->nomor_soal}";
            $isNewAudio = ($idx > 0 && $audioUrl !== $prevAudioUrl);
            $prevAudioUrl = $audioUrl;

            return [
                'id' => $s->id,
                'nomor_soal' => $s->nomor_soal,
                'pertanyaan' => $s->pertanyaan,
                'listening_audio_id' => $s->listening_audio_id,
                'audio_nomor' => $audioNomor,
                'audio_url' => $audioUrl,
                'audio_title' => $s->listeningAudio?->judul_audio ?: ($listening->audio_title ?? "Audio Percakapan Track #{$audioNomor}"),
                'durasi' => $s->listeningAudio?->durasi ?: ($s->durasi ?: ($listening->durasi ?? '01:30')),
                'rentang_soal' => $rentang,
                'is_new_audio' => $isNewAudio,
                'pilihan_a' => $s->pilihan_a,
                'pilihan_b' => $s->pilihan_b,
                'pilihan_c' => $s->pilihan_c,
                'pilihan_d' => $s->pilihan_d,
                'jawaban_benar' => $s->jawaban_benar,
                'pembahasan' => $s->pembahasan,
            ];
        });

        $audios = $listening->audios->map(function ($a) use ($rentangMap) {
            return [
                'id' => $a->id,
                'nomor_track' => $a->nomor_track,
                'judul_audio' => $a->judul_audio ?: "Audio Track #{$a->nomor_track}",
                'durasi' => $a->durasi,
                'audio_url' => $a->audio_url,
                'rentang_soal' => $rentangMap[$a->id] ?? null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Detail modul listening berhasil dimuat',
            'data' => [
                'id' => $listening->id,
                'judul' => $listening->judul,
                'deskripsi' => $listening->deskripsi,
                'tingkat_kesulitan' => $listening->tingkat_kesulitan,
                'tingkat_kelas' => $listening->tingkat_kelas,
                'audio_title' => $listening->audio_title ?? "Audio #{$listening->id}",
                'durasi' => $listening->durasi ?? '02:00',
                'audio_url' => $listening->audio_url,
                'audios' => $audios,
                'tujuan_belajar' => $listening->tujuan_belajar ?? [],
                'xp_reward' => $listening->xp_reward,
                'soals' => $soals,
            ],
        ]);
    }

    /**
     * Menyimpan penyelesaian modul listening siswa (+ reward XP).
     */
    public function selesaikan(SelesaikanListeningRequest $request, int $id): JsonResponse
    {
        $listening = ModulListening::with('soals')->find($id);

        if (! $listening) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paket modul listening tidak ditemukan!',
            ], 404);
        }

        $validated = $request->validated();
        $totalSoal = $listening->soals->count();

        // Anti-Cheat: Jika modul listening memiliki latihan soal, seluruh butir soal wajib dikerjakan
        if ($totalSoal > 0) {
            $userAnswers = $request->input('jawaban');
            $answeredCount = is_array($userAnswers)
                ? count(array_filter($userAnswers, fn ($v) => $v !== null && trim((string) $v) !== ''))
                : 0;

            if ($answeredCount < $totalSoal) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Seluruh {$totalSoal} butir latihan soal listening wajib diselesaikan sebelum menuntaskan modul!",
                ], 422);
            }

            // Hitung skor di sisi server
            $benar = 0;
            foreach ($listening->soals as $soal) {
                $userAns = strtoupper(trim((string) ($userAnswers[$soal->id] ?? $userAnswers[(string) $soal->id] ?? '')));
                $kunci = strtoupper(trim((string) $soal->jawaban_benar));
                if ($userAns !== '' && $userAns === $kunci) {
                    $benar++;
                }
            }
            $skor = (int) round(($benar / $totalSoal) * 100);
        } else {
            $skor = 100;
        }

        // Anti-Cheat: XP Reward selalu dari database
        $baseXp = (int) ($listening->xp_reward ?? 50);
        $xpDidapat = $totalSoal > 0 ? (int) round($baseXp * max(0.2, $skor / 100)) : $baseXp;
        $xpDidapat = max(5, $xpDidapat);

        $siswaId = $request->input('siswa_id');
        $validSiswa = $siswaId ? Siswa::find($siswaId) : null;
        $effectiveSiswaId = $validSiswa?->id;

        if ($effectiveSiswaId) {
            DB::transaction(function () use ($effectiveSiswaId, $listening, $skor, $xpDidapat) {
                SiswaListeningProgres::updateOrCreate(
                    ['siswa_id' => $effectiveSiswaId, 'modul_listening_id' => $listening->id],
                    [
                        'status' => 'selesai',
                        'skor' => $skor,
                        'xp_didapat' => $xpDidapat,
                        'completed_at' => now(),
                    ]
                );
            });
        }

        $totalAudio = ModulListening::where('status', 'aktif')->count();
        $audioSelesai = $effectiveSiswaId
            ? SiswaListeningProgres::where('siswa_id', $effectiveSiswaId)->where('status', 'selesai')->count()
            : 1;

        $persentase = $totalAudio > 0 ? (int) round(($audioSelesai / $totalAudio) * 100) : 0;

        return response()->json([
            'status' => 'success',
            'message' => 'Luar biasa! Modul Listening berhasil diselesaikan.',
            'data' => [
                'modul_listening_id' => $listening->id,
                'judul' => $listening->judul,
                'skor' => $skor,
                'xp_didapat' => $xpDidapat,
                'total_audio' => $totalAudio,
                'audio_selesai' => $audioSelesai,
                'persentase' => min($persentase, 100),
            ],
        ]);
    }

    /**
     * Mengambil ringkasan progres listening siswa.
     */
    public function progres(Request $request): JsonResponse
    {
        $siswaId = $request->input('siswa_id');
        $totalAudio = ModulListening::where('status', 'aktif')->count();

        $completedQuery = SiswaListeningProgres::where('status', 'selesai')
            ->with('modulListening')
            ->latest('completed_at');

        if ($siswaId) {
            $completedQuery->where('siswa_id', $siswaId);
        }

        $completedList = $completedQuery->get();
        $audioSelesai = $completedList->count();
        $persentase = $totalAudio > 0 ? (int) round(($audioSelesai / $totalAudio) * 100) : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_audio' => $totalAudio,
                'audio_selesai' => $audioSelesai,
                'persentase' => min($persentase, 100),
                'completed_listening_ids' => $completedList->pluck('modul_listening_id')->toArray(),
            ],
        ]);
    }
}
