<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModulListening;
use App\Models\Siswa;
use App\Models\SiswaListeningProgres;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModulListeningApiController extends Controller
{
    /**
     * Mengambil daftar track audio listening aktif untuk aplikasi Flutter siswa.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ModulListening::where('status', 'aktif')
            ->withCount('soals')
            ->latest('id');

        // Filter Tingkat Kesulitan
        if ($request->filled('tingkat') && ! in_array(strtolower($request->tingkat), ['all', 'semua', ''])) {
            $query->where('tingkat_kesulitan', ucfirst(strtolower($request->tingkat)));
        }

        // Filter Kelas Siswa
        if ($request->filled('kelas')) {
            $rawKelas = (string) $request->kelas;
            $tingkat = null;
            if (preg_match('/\b(10|11|12)\b/', $rawKelas, $matches)) {
                $tingkat = $matches[1];
            } elseif (preg_match('/\b(XII|XI|X)\b/i', $rawKelas, $matches)) {
                $romanMap = ['X' => '10', 'XI' => '11', 'XII' => '12'];
                $tingkat = $romanMap[strtoupper($matches[1])] ?? null;
            }

            $query->where(function ($q) use ($rawKelas, $tingkat) {
                $q->where('tingkat_kelas', 'Semua Kelas')
                    ->orWhere('tingkat_kelas', $rawKelas);
                if ($tingkat) {
                    $q->orWhere('tingkat_kelas', $tingkat);
                }
            });
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
     * Mengambil detail satu paket listening beserta transkrip, sasaran belajar, dan butir latihan soal.
     */
    public function show(int $id): JsonResponse
    {
        $listening = ModulListening::with('soals')->where('status', 'aktif')->find($id);

        if (! $listening) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paket modul listening tidak ditemukan atau belum aktif!',
            ], 404);
        }

        $soals = $listening->soals->map(function ($s) {
            return [
                'id' => $s->id,
                'nomor_soal' => $s->nomor_soal,
                'pertanyaan' => $s->pertanyaan,
                'pilihan_a' => $s->pilihan_a,
                'pilihan_b' => $s->pilihan_b,
                'pilihan_c' => $s->pilihan_c,
                'pilihan_d' => $s->pilihan_d,
                'jawaban_benar' => $s->jawaban_benar,
                'pembahasan' => $s->pembahasan,
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
                'transkrip' => $listening->transkrip,
                'tujuan_belajar' => $listening->tujuan_belajar ?? [],
                'xp_reward' => $listening->xp_reward,
                'soals' => $soals,
            ],
        ]);
    }

    /**
     * Menyimpan penyelesaian modul listening siswa (+ reward XP).
     */
    public function selesaikan(Request $request, int $id): JsonResponse
    {
        $listening = ModulListening::find($id);

        if (! $listening) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paket modul listening tidak ditemukan!',
            ], 404);
        }

        $siswaId = $request->input('siswa_id');
        $skor = $request->input('skor', 100);
        $xpReward = $request->input('xp_reward', $listening->xp_reward);

        $validSiswa = null;
        if ($siswaId) {
            $validSiswa = Siswa::find($siswaId);
        }

        if (! $validSiswa) {
            $validSiswa = Siswa::first();
        }

        $effectiveSiswaId = $validSiswa?->id;

        if ($effectiveSiswaId) {
            SiswaListeningProgres::updateOrCreate(
                ['siswa_id' => $effectiveSiswaId, 'modul_listening_id' => $listening->id],
                [
                    'status' => 'selesai',
                    'skor' => $skor,
                    'xp_didapat' => $xpReward,
                    'completed_at' => now(),
                ]
            );
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
                'xp_didapat' => $xpReward,
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
