<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FishingSoal;
use App\Models\Siswa;
use App\Models\SiswaFishingProgres;
use Illuminate\Http\Request;

class FishingApiController extends Controller
{
    /**
     * Mengambil daftar soal memancing (Word Fishing) berdasarkan level & tingkat kesulitan
     * GET /api/fishing/soal?level=1&difficulty=mudah
     */
    public function getSoal(Request $request)
    {
        $level = (int) $request->query('level', 1);
        $difficulty = strtolower($request->query('difficulty', 'mudah'));
        $limit = (int) $request->query('limit', 5);

        $query = FishingSoal::query();

        if ($level > 0) {
            $query->where('level', $level);
        }
        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }

        $soals = $query->inRandomOrder()->limit($limit)->get();

        // Jika soal di level tersebut belum diisi, fallback ambil soal acak dari difficulty yang sama
        if ($soals->isEmpty()) {
            $soals = FishingSoal::where('difficulty', $difficulty)
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        }

        return response()->json([
            'status' => 'success',
            'level' => $level,
            'difficulty' => $difficulty,
            'total' => $soals->count(),
            'data' => $soals,
        ]);
    }

    /**
     * Mengambil progres level & akuarium koleksi ikan siswa
     * GET /api/fishing/progres?siswa_id=...&difficulty=mudah
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

        // Ambil riwayat penyelesaian
        $progresMap = [];
        if ($siswaId) {
            $progresList = SiswaFishingProgres::where('siswa_id', $siswaId)
                ->where('difficulty', $difficulty)
                ->get();

            foreach ($progresList as $p) {
                $progresMap[$p->level] = $p;
            }
        }

        // Susun 9 level memancing
        $levels = [];
        $fishNames = [
            1 => ['name' => 'Golden Carp', 'emoji' => '🐟', 'indo' => 'Ikan Mas Koki'],
            2 => ['name' => 'Clownfish', 'emoji' => '🐠', 'indo' => 'Ikan Badut'],
            3 => ['name' => 'Blue Marlin', 'emoji' => '🐟', 'indo' => 'Ikan Todak Biru'],
            4 => ['name' => 'Electric Eel', 'emoji' => '⚡', 'indo' => 'Belut Listrik'],
            5 => ['name' => 'Giant Salmon', 'emoji' => '👑', 'indo' => 'Salmon Raksasa'],
            6 => ['name' => 'Rainbow Trout', 'emoji' => '🌈', 'indo' => 'Ikan Pelangi'],
            7 => ['name' => 'Tiger Shark', 'emoji' => '🦈', 'indo' => 'Hiu Harimau'],
            8 => ['name' => 'Hammerhead', 'emoji' => '🔨', 'indo' => 'Hiu Martil'],
            9 => ['name' => 'Leviathan Whale', 'emoji' => '🐋', 'indo' => 'Paus Raksasa'],
        ];

        for ($i = 1; $i <= 9; $i++) {
            $hasProgres = isset($progresMap[$i]);
            $isCompleted = $hasProgres ? (bool) $progresMap[$i]->is_completed : false;
            $stars = $hasProgres ? (int) $progresMap[$i]->stars : 0;
            $highScore = $hasProgres ? (int) $progresMap[$i]->high_score : 0;

            // Level 1 selalu terbuka, level berikutnya terbuka jika level sebelumnya sudah selesai
            $isUnlocked = ($i === 1) || (isset($progresMap[$i - 1]) && $progresMap[$i - 1]->is_completed);

            $fishInfo = $fishNames[$i] ?? ['name' => 'Mystery Fish', 'emoji' => '🐟', 'indo' => 'Ikan Misterius'];

            $levels[] = [
                'level' => $i,
                'difficulty' => $difficulty,
                'is_unlocked' => $isUnlocked,
                'is_completed' => $isCompleted,
                'stars' => $stars,
                'high_score' => $highScore,
                'fish_name' => $fishInfo['name'],
                'fish_indo' => $fishInfo['indo'],
                'fish_emoji' => $fishInfo['emoji'],
            ];
        }

        return response()->json([
            'status' => 'success',
            'koin' => $koin,
            'difficulty' => $difficulty,
            'data' => $levels,
        ]);
    }

    /**
     * Menyimpan hasil penangkapan ikan & koin reward (One-time coin rule)
     * POST /api/fishing/selesai
     */
    public function submitSelesai(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'nullable|integer',
            'difficulty' => 'required|string',
            'level' => 'required|integer',
            'stars' => 'required|integer|min:1|max:3',
            'score' => 'required|integer',
            'fish_name' => 'nullable|string',
            'fish_emoji' => 'nullable|string',
            'coins_earned' => 'required|integer',
        ]);

        $siswaId = $validated['siswa_id'] ?? null;
        $difficulty = strtolower($validated['difficulty']);
        $level = (int) $validated['level'];
        $stars = (int) $validated['stars'];
        $score = (int) $validated['score'];
        $fishName = $validated['fish_name'] ?? 'Golden Carp';
        $fishEmoji = $validated['fish_emoji'] ?? '🐟';
        $coinsEarned = (int) $validated['coins_earned'];

        // 1. Cek apakah level ini sudah pernah diselesaikan
        $progres = SiswaFishingProgres::firstOrNew([
            'siswa_id' => $siswaId,
            'difficulty' => $difficulty,
            'level' => $level,
        ]);

        $alreadyCompleted = (bool) ($progres->exists && $progres->is_completed);

        $progres->is_completed = true;
        $progres->fish_name = $fishName;
        $progres->fish_emoji = $fishEmoji;

        if ($stars > ($progres->stars ?? 0)) {
            $progres->stars = $stars;
        }
        if ($score > ($progres->high_score ?? 0)) {
            $progres->high_score = $score;
        }
        $progres->save();

        // 2. Koin reward hanya diberikan jika BELUM pernah selesai
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
                ? 'Ikan berhasil ditangkap! Koin level ini sudah pernah diklaim.'
                : 'Selamat! Ikan berhasil ditangkap dan koin bertambah!',
            'koin' => $currentCoins,
            'coins_awarded' => $actualCoinsAwarded,
            'data' => [
                'level' => $level,
                'difficulty' => $difficulty,
                'stars' => $progres->stars,
                'high_score' => $progres->high_score,
                'fish_name' => $fishName,
                'fish_emoji' => $fishEmoji,
                'is_completed' => true,
                'already_completed' => $alreadyCompleted,
                'coins_awarded' => $actualCoinsAwarded,
                'next_level_unlocked' => $level < 9 ? $level + 1 : null,
            ],
        ]);
    }
}
