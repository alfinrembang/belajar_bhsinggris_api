<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SentenceSoal;
use App\Models\Siswa;
use App\Models\SiswaSentenceHistory;
use App\Models\SiswaSentenceProgres;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SentenceApiController extends Controller
{
    /**
     * 1. Ambil 5 Soal Sesi Bermain (1 Soal Keluar Sekali per Akun Siswa)
     * GET /api/sentence/session-soals
     */
    public function getSessionSoals(Request $request): JsonResponse
    {
        $siswaId = $request->input('siswa_id');
        $difficulty = $request->input('difficulty'); // mudah, sedang, sulit, atau campuran/all
        $limit = (int) ($request->input('limit') ?: 5);

        // Ambil daftar ID soal yang SUDAH PERNAH DIKERJAKAN oleh siswa ini
        $answeredIds = [];
        if ($siswaId) {
            $answeredIds = SiswaSentenceHistory::where('siswa_id', $siswaId)
                ->pluck('sentence_soal_id')
                ->toArray();
        }

        // Query bank soal yang BELUM pernah dijawab oleh siswa ini
        $query = SentenceSoal::query();

        if (! empty($answeredIds)) {
            $query->whereNotIn('id', $answeredIds);
        }

        if (! empty($difficulty) && in_array(strtolower($difficulty), ['mudah', 'sedang', 'sulit'])) {
            $query->where('difficulty', strtolower($difficulty));
        }

        // Hitung total sisa soal yang belum dikerjakan
        $unansweredCount = (clone $query)->count();

        // Ambil acak sebanyak limit (default 5)
        $soals = $query->inRandomOrder()->take($limit)->get();

        // Jika bank soal baru yang belum dijawab habis (0) dan siswa ingin tetap bermain
        $allCompleted = false;
        if ($soals->isEmpty() && ! empty($answeredIds)) {
            $allCompleted = true;
            // Jika habis, ambil 5 soal secara umum agar siswa tidak macet/blank
            $fallbackQuery = SentenceSoal::query();
            if (! empty($difficulty) && in_array(strtolower($difficulty), ['mudah', 'sedang', 'sulit'])) {
                $fallbackQuery->where('difficulty', strtolower($difficulty));
            }
            $soals = $fallbackQuery->inRandomOrder()->take($limit)->get();
        }

        $formatted = $soals->map(function ($s) {
            return [
                'id' => $s->id,
                'level' => $s->level,
                'difficulty' => $s->difficulty,
                'sentence' => $s->sentence,
                'meaning' => $s->meaning,
                'tenses_category' => $s->tenses_category,
                'grammar_tips' => $s->grammar_tips,
                'audio_url' => $s->audio_url,
                'points' => $s->points,
                'shuffled_words' => $s->shuffled_words,
                'correct_words' => $s->correct_words,
            ];
        });

        return response()->json([
            'status' => 'success',
            'total' => $formatted->count(),
            'difficulty' => $difficulty ?: 'campuran',
            'all_completed' => $allCompleted,
            'remaining_unanswered' => max(0, $unansweredCount - $formatted->count()),
            'data' => $formatted,
        ]);
    }

    /**
     * 2. Simpan Hasil 1 Sesi Bermain (5 Soal) & Tambahkan Poin & Koin ke Akun Siswa
     * POST /api/sentence/submit-session
     */
    public function submitSession(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'siswa_id' => 'required|integer',
            'difficulty' => 'nullable|string',
            'score' => 'required|integer|min:0',
            'coins_earned' => 'required|integer|min:0',
            'correct_count' => 'required|integer|min:0|max:5',
            'seconds_used' => 'nullable|integer',
            'answered_soals' => 'required|array',
            'answered_soals.*.soal_id' => 'required|integer',
            'answered_soals.*.is_correct' => 'required|boolean',
            'answered_soals.*.score' => 'nullable|integer',
        ]);

        $siswaId = $validated['siswa_id'];
        $difficulty = $validated['difficulty'] ?? 'campuran';
        $score = $validated['score'];
        $coinsEarned = $validated['coins_earned'];
        $correctCount = $validated['correct_count'];
        $answeredSoals = $validated['answered_soals'];

        $siswa = Siswa::find($siswaId);
        if (! $siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan.'], 404);
        }

        // 1. Tambahkan Koin ke Akun Siswa
        $siswa->koin = max(0, ((int) ($siswa->koin ?? 0)) + $coinsEarned);
        $siswa->save();

        // 2. Catat Riwayat Soal agar TIDAK MUNCUL LAGI untuk siswa ini
        foreach ($answeredSoals as $item) {
            SiswaSentenceHistory::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'sentence_soal_id' => $item['soal_id'],
                ],
                [
                    'is_correct' => (bool) $item['is_correct'],
                    'score' => (int) ($item['score'] ?? 20),
                ]
            );
        }

        // 3. Update progres ringkasan siswa
        $progres = SiswaSentenceProgres::firstOrNew([
            'siswa_id' => $siswaId,
            'difficulty' => $difficulty,
            'level' => 1,
        ]);

        $progres->high_score = max($progres->high_score ?? 0, $score);
        $stars = 1;
        if ($correctCount >= 5) {
            $stars = 3;
        } elseif ($correctCount >= 3) {
            $stars = 2;
        }
        $progres->stars = max($progres->stars ?? 0, $stars);
        $progres->is_completed = true;
        $progres->save();

        $totalAnswered = SiswaSentenceHistory::where('siswa_id', $siswaId)->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Sesi permainan berhasil disimpan!',
            'data' => [
                'siswa_id' => $siswaId,
                'score' => $score,
                'coins_earned' => $coinsEarned,
                'new_koin' => $siswa->koin,
                'correct_count' => $correctCount,
                'stars' => $stars,
                'total_answered_unique' => $totalAnswered,
            ],
        ]);
    }

    /**
     * 3. Ambil Ringkasan Statistik Susun Kalimat Siswa
     * GET /api/sentence/stats
     */
    public function getStats(Request $request): JsonResponse
    {
        $siswaId = $request->input('siswa_id');
        if (! $siswaId) {
            return response()->json(['status' => 'error', 'message' => 'Parameter siswa_id wajib diisi.'], 400);
        }

        $siswa = Siswa::find($siswaId);
        $totalSoals = SentenceSoal::count();
        $answeredCount = SiswaSentenceHistory::where('siswa_id', $siswaId)->count();
        $correctCount = SiswaSentenceHistory::where('siswa_id', $siswaId)->where('is_correct', true)->count();
        $totalScore = SiswaSentenceHistory::where('siswa_id', $siswaId)->sum('score');

        return response()->json([
            'status' => 'success',
            'data' => [
                'siswa_id' => (int) $siswaId,
                'koin' => $siswa ? ($siswa->koin ?? 0) : 0,
                'total_soals' => $totalSoals,
                'total_answered' => $answeredCount,
                'total_correct' => $correctCount,
                'total_score' => (int) $totalScore,
                'remaining' => max(0, $totalSoals - $answeredCount),
            ],
        ]);
    }

    /**
     * 4. Reset Riwayat Soal (Opsional jika siswa ingin memainkan kembali dari awal)
     * POST /api/sentence/reset-history
     */
    public function resetHistory(Request $request): JsonResponse
    {
        $siswaId = $request->input('siswa_id');
        if (! $siswaId) {
            return response()->json(['status' => 'error', 'message' => 'Parameter siswa_id wajib diisi.'], 400);
        }

        SiswaSentenceHistory::where('siswa_id', $siswaId)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat soal berhasil di-reset. Semua soal dapat dimainkan kembali!',
        ]);
    }

    /**
     * Backward-compatible: getSoals (Level)
     */
    public function getSoals(Request $request): JsonResponse
    {
        return $this->getSessionSoals($request);
    }

    /**
     * Backward-compatible: getProgres (Level)
     */
    public function getProgres(Request $request): JsonResponse
    {
        return $this->getStats($request);
    }

    /**
     * Backward-compatible: submitLevel
     */
    public function submitLevel(Request $request): JsonResponse
    {
        return $this->submitSession($request);
    }
}
