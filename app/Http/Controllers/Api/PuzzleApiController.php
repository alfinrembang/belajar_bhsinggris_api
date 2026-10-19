<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SiswaPuzzleProgres;
use Illuminate\Http\Request;

class PuzzleApiController extends Controller
{
    /**
     * Mengambil daftar progres level puzzle siswa (?siswa_id=...&difficulty=mudah)
     */
    public function getProgres(Request $request)
    {
        $siswaId = $request->query('siswa_id');
        $difficulty = strtolower($request->query('difficulty', 'mudah'));

        $koin = 120;
        if ($siswaId) {
            $siswa = Siswa::find($siswaId);
            if ($siswa) {
                $koin = $siswa->koin ?? 120;
            }
        }

        // Ambil data progres dari database
        $query = SiswaPuzzleProgres::where('difficulty', $difficulty);
        if ($siswaId) {
            $query->where('siswa_id', $siswaId);
        }
        $progresList = $query->get()->keyBy('level');

        // Bentuk response 9 level dinamis
        $levels = [];
        $previousCompleted = true; // Level 1 selalu terbuka

        for ($i = 1; $i <= 9; $i++) {
            $record = $progresList->get($i);

            $isCompleted = $record ? (bool) $record->is_completed : false;
            $stars = $record ? (int) $record->stars : 0;
            $bestTime = $record ? $record->best_time : null;

            // Level terbuka jika ini level 1 ATAU level sebelumnya sudah selesai
            $isUnlocked = ($i === 1) || $previousCompleted;

            $levels[] = [
                'level' => $i,
                'difficulty' => $difficulty,
                'is_unlocked' => $isUnlocked,
                'is_completed' => $isCompleted,
                'stars' => $stars,
                'best_time' => $bestTime,
            ];

            $previousCompleted = $isCompleted;
        }

        return response()->json([
            'status' => 'success',
            'koin' => $koin,
            'difficulty' => $difficulty,
            'data' => $levels,
        ]);
    }

    /**
     * Menyimpan hasil penyelesaian puzzle siswa (+ koin reward & unlock level berikutnya)
     */
    public function submitSelesai(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'nullable|integer',
            'difficulty' => 'required|string',
            'level' => 'required|integer',
            'stars' => 'required|integer|min:1|max:3',
            'time_spent' => 'required|string',
            'coins_earned' => 'required|integer',
        ]);

        $siswaId = $validated['siswa_id'] ?? null;
        $difficulty = strtolower($validated['difficulty']);
        $level = (int) $validated['level'];
        $stars = (int) $validated['stars'];
        $timeSpent = $validated['time_spent'];
        $coinsEarned = (int) $validated['coins_earned'];

        // 1. Periksa apakah level ini sudah pernah diselesaikan sebelumnya
        $progres = SiswaPuzzleProgres::firstOrNew([
            'siswa_id' => $siswaId,
            'difficulty' => $difficulty,
            'level' => $level,
        ]);

        $alreadyCompleted = (bool) ($progres->exists && $progres->is_completed);

        $progres->is_completed = true;
        // Simpan bintang tertinggi
        if ($stars > ($progres->stars ?? 0)) {
            $progres->stars = $stars;
        }
        // Simpan best time (jika baru atau lebih cepat)
        if (! $progres->best_time || strcmp($timeSpent, $progres->best_time) < 0) {
            $progres->best_time = $timeSpent;
        }
        $progres->save();

        // 2. Tambah koin HANYA jika BELUM pernah diselesaikan sebelumnya (Reward 1x)
        $actualCoinsAwarded = $alreadyCompleted ? 0 : $coinsEarned;

        $currentCoins = 120;
        if ($siswaId) {
            $siswa = Siswa::find($siswaId);
            if ($siswa) {
                if ($actualCoinsAwarded > 0) {
                    $siswa->koin = ($siswa->koin ?? 120) + $actualCoinsAwarded;
                    $siswa->save();
                }
                $currentCoins = $siswa->koin;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => $alreadyCompleted
                ? 'Level selesai! Koin sudah diklaim sebelumnya.'
                : 'Progres puzzle berhasil disimpan!',
            'koin' => $currentCoins,
            'coins_awarded' => $actualCoinsAwarded,
            'data' => [
                'level' => $level,
                'difficulty' => $difficulty,
                'stars' => $progres->stars,
                'best_time' => $progres->best_time,
                'is_completed' => true,
                'already_completed' => $alreadyCompleted,
                'coins_awarded' => $actualCoinsAwarded,
                'next_level_unlocked' => $level < 9 ? $level + 1 : null,
            ],
        ]);
    }
}
