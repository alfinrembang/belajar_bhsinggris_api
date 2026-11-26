<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SiswaFishingProgres;
use App\Models\SiswaListeningProgres;
use App\Models\SiswaMateriProgres;
use App\Models\SiswaQuizProgres;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AktivitasApiController extends Controller
{
    /**
     * Mengambil riwayat aktivitas belajar siswa secara dinamis.
     * Menggabungkan riwayat: Materi, Listening, Kuis, dan Permainan Edukasi (Game).
     *
     * Query parameters:
     * - siswa_id: ID siswa (wajib atau dari token)
     * - limit: Jumlah data yang dikembalikan (default: 5, 'all' atau 0 untuk semua)
     * - type: Filter jenis aktivitas ('all', 'materi', 'listening', 'quiz', 'game')
     */
    public function index(Request $request): JsonResponse
    {
        Carbon::setLocale('id');

        $siswaId = $request->input('siswa_id');

        if (! $siswaId && auth('sanctum')->check()) {
            $siswaId = auth('sanctum')->id();
        }

        if (! $siswaId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter siswa_id wajib disertakan!',
            ], 422);
        }

        $typeFilter = $request->input('type', 'all');
        $limit = $request->input('limit', 5);
        if ($limit === 'all') {
            $limit = 0;
        } else {
            $limit = (int) $limit;
        }

        $allActivities = collect();

        // 1. Aktivitas Materi Pembelajaran
        if ($typeFilter === 'all' || $typeFilter === 'materi') {
            $materiList = SiswaMateriProgres::with('materi')
                ->where('siswa_id', $siswaId)
                ->where(function ($q) {
                    $q->where('status', 'selesai')
                        ->orWhereNotNull('completed_at');
                })
                ->get()
                ->map(function ($item) {
                    $date = $item->completed_at ?? $item->updated_at ?? $item->created_at;
                    $carbonDate = $date ? Carbon::parse($date)->locale('id') : null;

                    return [
                        'id' => 'materi_' . $item->id,
                        'source_id' => $item->materi_id,
                        'tipe' => 'materi',
                        'judul' => 'Materi – ' . ($item->materi?->judul ?? 'Materi Belajar'),
                        'subjudul' => $item->materi?->kategori ?? 'Reading & Grammar',
                        'status' => 'selesai',
                        'badge_text' => 'selesai',
                        'skor' => $item->skor,
                        'xp_didapat' => $item->xp_didapat ?? 0,
                        'tanggal' => $carbonDate ? $carbonDate->translatedFormat('d M Y') : '-',
                        'waktu_lalu' => $carbonDate ? $carbonDate->diffForHumans() : '-',
                        'raw_datetime' => $carbonDate ? $carbonDate->toISOString() : null,
                        'timestamp' => $carbonDate ? $carbonDate->timestamp : 0,
                    ];
                });

            $allActivities = $allActivities->merge($materiList);
        }

        // 2. Aktivitas Modul Listening
        if ($typeFilter === 'all' || $typeFilter === 'listening') {
            $listeningList = SiswaListeningProgres::with('modulListening')
                ->where('siswa_id', $siswaId)
                ->where(function ($q) {
                    $q->where('status', 'selesai')
                        ->orWhereNotNull('completed_at');
                })
                ->get()
                ->map(function ($item) {
                    $date = $item->completed_at ?? $item->updated_at ?? $item->created_at;
                    $carbonDate = $date ? Carbon::parse($date)->locale('id') : null;

                    $badge = 'selesai';
                    if ($item->skor !== null && $item->skor > 0) {
                        $badge = 'Skor ' . $item->skor;
                    }

                    return [
                        'id' => 'listening_' . $item->id,
                        'source_id' => $item->modul_listening_id,
                        'tipe' => 'listening',
                        'judul' => 'Listening – ' . ($item->modulListening?->judul ?? 'Daily Practice'),
                        'subjudul' => $item->modulListening?->tingkat_kesulitan ? 'Tingkat: ' . $item->modulListening->tingkat_kesulitan : 'Audio Percakapan',
                        'status' => 'selesai',
                        'badge_text' => $badge,
                        'skor' => $item->skor,
                        'xp_didapat' => $item->xp_didapat ?? 0,
                        'tanggal' => $carbonDate ? $carbonDate->translatedFormat('d M Y') : '-',
                        'waktu_lalu' => $carbonDate ? $carbonDate->diffForHumans() : '-',
                        'raw_datetime' => $carbonDate ? $carbonDate->toISOString() : null,
                        'timestamp' => $carbonDate ? $carbonDate->timestamp : 0,
                    ];
                });

            $allActivities = $allActivities->merge($listeningList);
        }

        // 3. Aktivitas Bank Soal & Kuis
        if ($typeFilter === 'all' || $typeFilter === 'quiz') {
            $quizList = SiswaQuizProgres::with('quiz')
                ->where('siswa_id', $siswaId)
                ->get()
                ->map(function ($item) {
                    $date = $item->completed_at ?? $item->updated_at ?? $item->created_at;
                    $carbonDate = $date ? Carbon::parse($date)->locale('id') : null;

                    $badge = $item->skor !== null ? 'Skor ' . $item->skor : 'selesai';

                    return [
                        'id' => 'quiz_' . $item->id,
                        'source_id' => $item->quiz_id,
                        'tipe' => 'quiz',
                        'judul' => 'Kuis – ' . ($item->quiz?->judul ?? 'Kuis Evaluasi'),
                        'subjudul' => $item->quiz?->kategori ?? 'Latihan Soal',
                        'status' => 'selesai',
                        'badge_text' => $badge,
                        'skor' => $item->skor,
                        'xp_didapat' => $item->xp_didapat ?? 0,
                        'tanggal' => $carbonDate ? $carbonDate->translatedFormat('d M Y') : '-',
                        'waktu_lalu' => $carbonDate ? $carbonDate->diffForHumans() : '-',
                        'raw_datetime' => $carbonDate ? $carbonDate->toISOString() : null,
                        'timestamp' => $carbonDate ? $carbonDate->timestamp : 0,
                    ];
                });

            $allActivities = $allActivities->merge($quizList);
        }

        // 4. Aktivitas Game (Word Fishing)
        if ($typeFilter === 'all' || $typeFilter === 'game') {
            $gameList = SiswaFishingProgres::where('siswa_id', $siswaId)
                ->where(function ($q) {
                    $q->where('is_completed', true)
                        ->orWhere('high_score', '>', 0);
                })
                ->get()
                ->map(function ($item) {
                    $date = $item->updated_at ?? $item->created_at;
                    $carbonDate = $date ? Carbon::parse($date)->locale('id') : null;

                    $badge = $item->stars > 0 ? $item->stars . ' ★' : 'selesai';

                    return [
                        'id' => 'game_' . $item->id,
                        'source_id' => $item->id,
                        'tipe' => 'game',
                        'judul' => 'Game – Level ' . $item->level . ' (' . ucfirst($item->difficulty) . ')',
                        'subjudul' => 'Word Fishing · ' . ($item->fish_name ? $item->fish_emoji . ' ' . $item->fish_name : 'Pancing Kata'),
                        'status' => 'selesai',
                        'badge_text' => $badge,
                        'skor' => $item->high_score,
                        'xp_didapat' => 0,
                        'tanggal' => $carbonDate ? $carbonDate->translatedFormat('d M Y') : '-',
                        'waktu_lalu' => $carbonDate ? $carbonDate->diffForHumans() : '-',
                        'raw_datetime' => $carbonDate ? $carbonDate->toISOString() : null,
                        'timestamp' => $carbonDate ? $carbonDate->timestamp : 0,
                    ];
                });

            $allActivities = $allActivities->merge($gameList);
        }

        // Urutkan dari aktivitas paling mutakhir (terbaru)
        $sortedActivities = $allActivities->sortByDesc('timestamp')->values();

        $resultData = $limit > 0 ? $sortedActivities->take($limit) : $sortedActivities;

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat aktivitas terbaru siswa berhasil dimuat',
            'total_aktivitas' => $sortedActivities->count(),
            'limit' => $limit,
            'data' => $resultData,
        ]);
    }
}
