<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PronounceRoom;
use App\Models\PronounceRoomPlayer;
use App\Models\PronounceSubmission;
use App\Models\PronounceVote;
use App\Models\PronounceWord;
use App\Models\Siswa;
use App\Models\SiswaPronounceProgres;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PronounceApiController extends Controller
{
    /**
     * 1. Ambil Daftar Kata Pelafalan (Mode Single Player & Mabar)
     */
    public function getWords(Request $request): JsonResponse
    {
        $difficulty = $request->input('difficulty', 'all');
        $siswaId = $request->input('siswa_id');

        $query = PronounceWord::query();

        if ($difficulty !== 'all' && in_array($difficulty, ['mudah', 'sedang', 'sulit'])) {
            $query->where('difficulty', $difficulty);
        }

        $words = $query->get()->map(function ($word) use ($siswaId) {
            $progres = null;
            if ($siswaId) {
                $progres = SiswaPronounceProgres::where('siswa_id', $siswaId)
                    ->where('pronounce_word_id', $word->id)
                    ->first();
            }

            return [
                'id' => $word->id,
                'word' => $word->word,
                'phonetic' => $word->phonetic,
                'part_of_speech' => $word->part_of_speech,
                'meaning' => $word->meaning,
                'difficulty' => $word->difficulty,
                'audio_url' => $word->audio_url,
                'tips' => $word->tips,
                'example_sentence' => $word->example_sentence,
                'best_accuracy' => $progres ? $progres->best_accuracy : 0,
                'stars' => $progres ? $progres->stars : 0,
                'is_completed' => $progres && $progres->best_accuracy >= 70,
            ];
        });

        return response()->json([
            'status' => 'success',
            'total' => $words->count(),
            'data' => $words,
        ]);
    }

    /**
     * 2. Simpan Skor Mode Single Player (Dinilai Bot AI)
     */
    public function submitSingle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'siswa_id' => 'required|integer',
            'word_id' => 'required|integer',
            'accuracy' => 'required|integer|min:0|max:100',
        ]);

        $siswaId = $validated['siswa_id'];
        $wordId = $validated['word_id'];
        $accuracy = $validated['accuracy'];

        $word = PronounceWord::find($wordId);
        if (! $word) {
            return response()->json(['status' => 'error', 'message' => 'Kata tidak ditemukan'], 404);
        }

        $siswa = Siswa::find($siswaId);
        if (! $siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan'], 404);
        }

        // Tentukan bintang dan reward
        $stars = 0;
        $xpEarned = 5;
        $coinsEarned = 0;

        if ($accuracy >= 90) {
            $stars = 3;
            $xpEarned = 35;
            $coinsEarned = 10;
        } elseif ($accuracy >= 75) {
            $stars = 2;
            $xpEarned = 20;
            $coinsEarned = 5;
        } elseif ($accuracy >= 50) {
            $stars = 1;
            $xpEarned = 10;
            $coinsEarned = 2;
        }

        $progres = SiswaPronounceProgres::firstOrNew([
            'siswa_id' => $siswaId,
            'pronounce_word_id' => $wordId,
        ]);

        $isNewBest = false;
        if (! $progres->exists || $accuracy > $progres->best_accuracy) {
            $progres->best_accuracy = $accuracy;
            $progres->stars = max($progres->stars, $stars);
            $progres->xp_didapat = ($progres->xp_didapat ?? 0) + $xpEarned;
            $progres->save();
            $isNewBest = true;

            // Beri koin & XP ke siswa
            if ($coinsEarned > 0) {
                $siswa->increment('koin', $coinsEarned);
            }
            if ($xpEarned > 0) {
                $siswa->increment('xp', $xpEarned);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Hasil pelafalan berhasil disimpan!',
            'data' => [
                'accuracy' => $accuracy,
                'stars' => $stars,
                'xp_earned' => $xpEarned,
                'coins_earned' => $coinsEarned,
                'is_new_best' => $isNewBest,
                'total_koin' => $siswa->koin,
            ],
        ]);
    }

    /**
     * 3. Buat Room Mabar Baru (Maksimal 5 Player)
     */
    public function createRoom(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'host_id' => 'required|integer',
            'title' => 'nullable|string|max:100',
        ]);

        $host = Siswa::find($validated['host_id']);
        if (! $host) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan'], 404);
        }

        // Generate 6-digit uppercase code
        do {
            $code = strtoupper(Str::random(6));
        } while (PronounceRoom::where('code', $code)->exists());

        $room = PronounceRoom::create([
            'code' => $code,
            'host_id' => $host->id,
            'title' => $validated['title'] ?? 'Room Adu Lafal ' . $host->nama_lengkap,
            'status' => 'waiting',
            'max_players' => 5,
            'current_round' => 1,
            'total_rounds' => 5,
            'current_turn_siswa_id' => $host->id,
        ]);

        // Host otomatis masuk sebagai player 1
        PronounceRoomPlayer::create([
            'room_id' => $room->id,
            'siswa_id' => $host->id,
            'total_score' => 0,
            'is_ready' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Room berhasil dibuat!',
            'data' => $this->formatRoomDetails($room),
        ]);
    }

    /**
     * 4. Gabung Room Mabar Menggunakan Kode 6 Digit
     */
    public function joinRoom(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'siswa_id' => 'required|integer',
        ]);

        $code = strtoupper(trim($validated['code']));
        $room = PronounceRoom::where('code', $code)->first();

        if (! $room) {
            return response()->json(['status' => 'error', 'message' => 'Kode room tidak ditemukan!'], 404);
        }

        if ($room->status === 'finished') {
            return response()->json(['status' => 'error', 'message' => 'Room ini sudah selesai bermain.'], 400);
        }

        // Cek jika player sudah ada di dalam room
        $existing = PronounceRoomPlayer::where('room_id', $room->id)
            ->where('siswa_id', $validated['siswa_id'])
            ->first();

        if (! $existing) {
            // Cek batas kuota maksimal 5 player
            $currentCount = PronounceRoomPlayer::where('room_id', $room->id)->count();
            if ($currentCount >= $room->max_players) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Room sudah penuh! Maksimal hanya ' . $room->max_players . ' pemain.',
                ], 400);
            }

            PronounceRoomPlayer::create([
                'room_id' => $room->id,
                'siswa_id' => $validated['siswa_id'],
                'total_score' => 0,
                'is_ready' => false,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil bergabung ke room!',
            'data' => $this->formatRoomDetails($room),
        ]);
    }

    /**
     * 5. Ambil Detail & Status Terkini Room
     */
    public function getRoom(string $code): JsonResponse
    {
        $room = PronounceRoom::where('code', strtoupper($code))->first();

        if (! $room) {
            return response()->json(['status' => 'error', 'message' => 'Room tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $this->formatRoomDetails($room),
        ]);
    }

    /**
     * 6. Toggle Status Siap / Ready Pemain
     */
    public function toggleReady(Request $request, string $code): JsonResponse
    {
        $validated = $request->validate(['siswa_id' => 'required|integer']);

        $room = PronounceRoom::where('code', strtoupper($code))->first();
        if (! $room) {
            return response()->json(['status' => 'error', 'message' => 'Room tidak ditemukan'], 404);
        }

        $player = PronounceRoomPlayer::where('room_id', $room->id)
            ->where('siswa_id', $validated['siswa_id'])
            ->first();

        if ($player) {
            $player->is_ready = ! $player->is_ready;
            $player->save();
        }

        return response()->json([
            'status' => 'success',
            'is_ready' => $player ? $player->is_ready : false,
            'data' => $this->formatRoomDetails($room),
        ]);
    }

    /**
     * 7. Host Memulai Game
     */
    public function startGame(Request $request, string $code): JsonResponse
    {
        $validated = $request->validate(['host_id' => 'required|integer']);

        $room = PronounceRoom::where('code', strtoupper($code))->first();
        if (! $room || $room->host_id != $validated['host_id']) {
            return response()->json(['status' => 'error', 'message' => 'Hanya Host yang dapat memulai permainan!'], 403);
        }

        // Pilih kata acak untuk ronde pertama
        $randomWord = PronounceWord::inRandomOrder()->first();

        // Tentukan giliran pemain pertama
        $firstPlayer = PronounceRoomPlayer::where('room_id', $room->id)->first();

        $room->status = 'playing';
        $room->current_round = 1;
        $room->current_word_id = $randomWord?->id;
        $room->current_turn_siswa_id = $firstPlayer?->siswa_id ?? $room->host_id;
        $room->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Permainan adu lafal dimulai!',
            'data' => $this->formatRoomDetails($room),
        ]);
    }

    /**
     * 8. Pemain Mengunggah Rekaman Suara VN
     */
    public function recordSubmission(Request $request, string $code): JsonResponse
    {
        $validated = $request->validate([
            'siswa_id' => 'required|integer',
            'round' => 'required|integer',
            'word_id' => 'required|integer',
            'ai_accuracy' => 'nullable|integer',
            'audio_base64' => 'nullable|string',
        ]);

        $room = PronounceRoom::where('code', strtoupper($code))->first();
        if (! $room) {
            return response()->json(['status' => 'error', 'message' => 'Room tidak ditemukan'], 404);
        }

        $audioUrl = null;
        $audioPath = null;

        // Jika ada data audio base64, simpan sebagai file audio m4a/aac ringan
        if (! empty($validated['audio_base64'])) {
            $audioData = base64_decode(preg_replace('#^data:audio/\w+;base64,#i', '', $validated['audio_base64']));
            $filename = 'pronounce_vn_' . $room->id . '_r' . $validated['round'] . '_s' . $validated['siswa_id'] . '_' . time() . '.m4a';
            $audioPath = 'voice_game/' . $filename;
            Storage::disk('public')->put($audioPath, $audioData);
            $audioUrl = url(Storage::url($audioPath));
        }

        $submission = PronounceSubmission::create([
            'room_id' => $room->id,
            'round' => $validated['round'],
            'siswa_id' => $validated['siswa_id'],
            'word_id' => $validated['word_id'],
            'audio_path' => $audioPath,
            'audio_url' => $audioUrl,
            'ai_accuracy' => $validated['ai_accuracy'] ?? null,
            'average_score' => 0.0,
            'total_voters' => 0,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Rekaman suara berhasil dikirim ke room!',
            'submission_id' => $submission->id,
            'data' => $this->formatRoomDetails($room),
        ]);
    }

    /**
     * 9. Pemain Lain Memberi Nilai Voting (Skor 1 - 10)
     */
    public function submitVote(Request $request, string $code): JsonResponse
    {
        $validated = $request->validate([
            'submission_id' => 'required|integer',
            'voter_siswa_id' => 'required|integer',
            'score' => 'required|integer|min:1|max:10',
        ]);

        $submission = PronounceSubmission::find($validated['submission_id']);
        if (! $submission) {
            return response()->json(['status' => 'error', 'message' => 'Rekaman tidak ditemukan'], 404);
        }

        // Simpan vote
        PronounceVote::updateOrCreate(
            [
                'submission_id' => $submission->id,
                'voter_siswa_id' => $validated['voter_siswa_id'],
            ],
            ['score' => $validated['score']]
        );

        // Hitung rata-rata vote terbaru
        $avg = PronounceVote::where('submission_id', $submission->id)->avg('score');
        $count = PronounceVote::where('submission_id', $submission->id)->count();

        $submission->average_score = round($avg, 1);
        $submission->total_voters = $count;
        $submission->save();

        // Update total score pemain yang melafalkan di tabel player
        $totalPlayerScore = PronounceSubmission::where('room_id', $submission->room_id)
            ->where('siswa_id', $submission->siswa_id)
            ->sum('average_score');

        PronounceRoomPlayer::where('room_id', $submission->room_id)
            ->where('siswa_id', $submission->siswa_id)
            ->update(['total_score' => (int) round($totalPlayerScore * 10)]);

        $room = PronounceRoom::find($submission->room_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Vote skor ' . $validated['score'] . ' berhasil dicatat!',
            'average_score' => $submission->average_score,
            'total_voters' => $submission->total_voters,
            'data' => $room ? $this->formatRoomDetails($room) : null,
        ]);
    }

    /**
     * 10. Lanjut ke Giliran Pemain Berikutnya / Ronde Baru
     */
    public function nextTurn(Request $request, string $code): JsonResponse
    {
        $room = PronounceRoom::where('code', strtoupper($code))->first();
        if (! $room) {
            return response()->json(['status' => 'error', 'message' => 'Room tidak ditemukan'], 404);
        }

        $players = PronounceRoomPlayer::where('room_id', $room->id)->orderBy('id')->get();
        $playerIds = $players->pluck('siswa_id')->toArray();

        $currentIndex = array_search($room->current_turn_siswa_id, $playerIds);
        if ($currentIndex === false || $currentIndex >= count($playerIds) - 1) {
            // Semua pemain sudah giliran di ronde ini -> lanjut ke ronde berikutnya
            if ($room->current_round >= $room->total_rounds) {
                $room->status = 'finished';
                $room->save();

                // Bersihkan file audio sementara mabar agar server tetap enteng
                $this->cleanupRoomAudio($room->id);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Permainan selesai!',
                    'data' => $this->formatRoomDetails($room),
                ]);
            } else {
                $room->current_round += 1;
                $room->current_turn_siswa_id = $playerIds[0];
                $newWord = PronounceWord::inRandomOrder()->first();
                $room->current_word_id = $newWord?->id;
                $room->save();
            }
        } else {
            // Giliran pemain berikutnya dalam ronde yang sama
            $room->current_turn_siswa_id = $playerIds[$currentIndex + 1];
            $newWord = PronounceWord::inRandomOrder()->first();
            $room->current_word_id = $newWord?->id;
            $room->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Giliran berhasil diperbarui',
            'data' => $this->formatRoomDetails($room),
        ]);
    }

    /**
     * Helper: Format detail room lengkap beserta daftar pemain dan kata aktif
     */
    private function formatRoomDetails(PronounceRoom $room): array
    {
        $room->load(['host', 'currentTurnSiswa', 'currentWord', 'players.siswa']);

        $playersData = $room->players->map(function ($p) use ($room) {
            return [
                'siswa_id' => $p->siswa_id,
                'nama' => $p->siswa?->nama_lengkap ?? 'Pemain',
                'nis' => $p->siswa?->nis,
                'kelas' => $p->siswa?->kelas,
                'total_score' => $p->total_score,
                'is_ready' => (bool) $p->is_ready,
                'is_host' => $p->siswa_id == $room->host_id,
                'is_current_turn' => $p->siswa_id == $room->current_turn_siswa_id,
            ];
        });

        // Ambil submission terakhir untuk ronde & kata saat ini jika ada
        $latestSubmission = PronounceSubmission::with(['siswa', 'votes'])
            ->where('room_id', $room->id)
            ->where('round', $room->current_round)
            ->latest('id')
            ->first();

        $submissionData = null;
        if ($latestSubmission) {
            $submissionData = [
                'id' => $latestSubmission->id,
                'siswa_id' => $latestSubmission->siswa_id,
                'nama_siswa' => $latestSubmission->siswa?->nama_lengkap ?? 'Pemain',
                'audio_url' => $latestSubmission->audio_url,
                'ai_accuracy' => $latestSubmission->ai_accuracy,
                'average_score' => (float) $latestSubmission->average_score,
                'total_voters' => $latestSubmission->total_voters,
                'voters' => $latestSubmission->votes->pluck('voter_siswa_id')->toArray(),
            ];
        }

        return [
            'id' => $room->id,
            'code' => $room->code,
            'title' => $room->title,
            'status' => $room->status,
            'host_id' => $room->host_id,
            'host_nama' => $room->host?->nama_lengkap ?? 'Host',
            'max_players' => $room->max_players,
            'player_count' => $playersData->count(),
            'current_round' => $room->current_round,
            'total_rounds' => $room->total_rounds,
            'current_turn_siswa_id' => $room->current_turn_siswa_id,
            'current_turn_nama' => $room->currentTurnSiswa?->nama_lengkap,
            'current_word' => $room->currentWord ? [
                'id' => $room->currentWord->id,
                'word' => $room->currentWord->word,
                'phonetic' => $room->currentWord->phonetic,
                'meaning' => $room->currentWord->meaning,
                'tips' => $room->currentWord->tips,
                'difficulty' => $room->currentWord->difficulty,
                'example_sentence' => $room->currentWord->example_sentence,
            ] : null,
            'players' => $playersData,
            'latest_submission' => $submissionData,
        ];
    }

    /**
     * Helper: Menghapus file audio VN mabar setelah game selesai (auto-cleanup)
     */
    private function cleanupRoomAudio(int $roomId): void
    {
        try {
            $subs = PronounceSubmission::where('room_id', $roomId)
                ->whereNotNull('audio_path')
                ->get();

            foreach ($subs as $sub) {
                if ($sub->audio_path && Storage::disk('public')->exists($sub->audio_path)) {
                    Storage::disk('public')->delete($sub->audio_path);
                }
            }
        } catch (\Exception $e) {
            // Ignore error
        }
    }
}
