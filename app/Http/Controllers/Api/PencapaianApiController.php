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

class PencapaianApiController extends Controller
{
    /**
     * Mengambil daftar seluruh pencapaian / achievement siswa secara dinamis.
     * GET /api/pencapaian?siswa_id=...
     */
    public function index(Request $request): JsonResponse
    {
        $siswaId = $request->query('siswa_id');

        // Cari siswa berdasarkan ID, jika tidak ada cari siswa pertama sebagai fallback
        $siswa = null;
        if ($siswaId) {
            $siswa = Siswa::find($siswaId);
        }
        if (! $siswa) {
            $siswa = Siswa::first();
        }

        if (! $siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data siswa tidak ditemukan.',
                'summary' => [
                    'total_terbuka' => 0,
                    'total_terkunci' => 0,
                    'selesai' => 0,
                    'total_lencana' => 0,
                    'persentase' => 0,
                ],
                'data' => [],
            ], 200);
        }

        $sid = $siswa->id;

        // 1. STATISTIK AKTIVITAS BELAJAR SISWA
        // Progres Materi
        $completedMateriCount = SiswaMateriProgres::where('siswa_id', $sid)
            ->where('status', 'selesai')
            ->count();

        // Progres Listening
        $completedListeningCount = SiswaListeningProgres::where('siswa_id', $sid)
            ->where('status', 'selesai')
            ->count();

        // Progres Kuis
        $quizProgresList = SiswaQuizProgres::where('siswa_id', $sid)->get();
        $completedQuizCount = $quizProgresList->count();
        $passedQuizCount = $quizProgresList->where('skor', '>=', 75)->count();
        $hasPerfectScore = $quizProgresList->where('skor', '>=', 100)->isNotEmpty();
        $avgQuizScore = $completedQuizCount > 0 ? (float) $quizProgresList->avg('skor') : 0.0;
        $totalQuizBenar = (int) $quizProgresList->sum('jumlah_benar');

        // Progres Game Word Fishing
        $fishingProgresList = SiswaFishingProgres::where('siswa_id', $sid)->get();
        $completedFishingLevels = $fishingProgresList->where('is_completed', true)->count();
        $totalFishingScore = (int) $fishingProgresList->sum('high_score');
        $totalGamePoints = $totalFishingScore; // Poin akumulasi game

        // Hitung penguasaan kosakata: butir kuis benar + level memancing * 5
        $totalVocabLearned = $totalQuizBenar + ($completedFishingLevels * 5);

        // 2. HITUNG STREAK HARI BELAJAR
        // Kumpulkan seluruh tanggal aktivitas pengerjaan
        $activityDates = collect();

        $materiDates = SiswaMateriProgres::where('siswa_id', $sid)
            ->whereNotNull('completed_at')
            ->pluck('completed_at');
        foreach ($materiDates as $d) {
            $activityDates->push(Carbon::parse($d)->toDateString());
        }

        $listeningDates = SiswaListeningProgres::where('siswa_id', $sid)
            ->whereNotNull('completed_at')
            ->pluck('completed_at');
        foreach ($listeningDates as $d) {
            $activityDates->push(Carbon::parse($d)->toDateString());
        }

        $quizDates = SiswaQuizProgres::where('siswa_id', $sid)
            ->whereNotNull('completed_at')
            ->pluck('completed_at');
        foreach ($quizDates as $d) {
            $activityDates->push(Carbon::parse($d)->toDateString());
        }

        $fishingDates = SiswaFishingProgres::where('siswa_id', $sid)
            ->whereNotNull('updated_at')
            ->pluck('updated_at');
        foreach ($fishingDates as $d) {
            $activityDates->push(Carbon::parse($d)->toDateString());
        }

        // Sertakan tanggal login / updated_at siswa jika pernah aktif
        if ($siswa->updated_at) {
            $activityDates->push($siswa->updated_at->toDateString());
        }

        $uniqueDates = $activityDates->unique()->sortDesc()->values();

        // Hitung streak hari berturut-turut
        $streakDays = 0;
        if ($uniqueDates->isNotEmpty()) {
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $firstDate = Carbon::parse($uniqueDates->first());

            // Jika ada aktivitas hari ini atau kemarin, mulai hitung streak
            if ($firstDate->isSameDay($today) || $firstDate->isSameDay($yesterday)) {
                $checkDate = $firstDate->copy();
                foreach ($uniqueDates as $dStr) {
                    $d = Carbon::parse($dStr);
                    if ($d->isSameDay($checkDate)) {
                        $streakDays++;
                        $checkDate->subDay();
                    } else {
                        break;
                    }
                }
            } else {
                // Aktivitas lampau
                $streakDays = min(count($uniqueDates), 3);
            }
        }
        // Minimal 1 hari jika siswa memiliki riwayat belajar apapun
        if ($streakDays == 0 && ($completedMateriCount > 0 || $completedQuizCount > 0 || $totalGamePoints > 0)) {
            $streakDays = 1;
        }

        // 3. CEK AKTIVITAS EARLY BIRD (sebelum 07:00 pagi)
        $hasEarlyBird = false;
        $allTimes = collect()
            ->merge(SiswaMateriProgres::where('siswa_id', $sid)->whereNotNull('completed_at')->pluck('completed_at'))
            ->merge(SiswaQuizProgres::where('siswa_id', $sid)->whereNotNull('completed_at')->pluck('completed_at'))
            ->merge(SiswaListeningProgres::where('siswa_id', $sid)->whereNotNull('completed_at')->pluck('completed_at'));

        foreach ($allTimes as $time) {
            $hour = Carbon::parse($time)->hour;
            if ($hour >= 4 && $hour < 7) {
                $hasEarlyBird = true;
                break;
            }
        }

        // 4. CEK PERINGKAT TOP 10
        // Cek posisi siswa di papan skor game
        $allScores = SiswaFishingProgres::selectRaw('siswa_id, sum(high_score) as total_skor')
            ->groupBy('siswa_id')
            ->orderByDesc('total_skor')
            ->limit(10)
            ->pluck('siswa_id')
            ->toArray();
        $isTop10 = in_array($sid, $allScores) || count($allScores) < 10;

        // 5. CEK DETEKTIF WOLF (jika siswa punya game progress atau koin)
        $hasWolfGame = ($siswa->koin ?? 0) > 0 || $completedFishingLevels > 0;

        $kelasLengkap = $siswa->kelas_lengkap ?: 'Kelas';

        // 6. DAFTAR 13 LENCANA PENCAPAIAN
        $badges = [];

        // 1. Streak King (7 Hari Berturut-turut)
        $targetStreak = 7;
        $isStreakUnlocked = $streakDays >= $targetStreak;
        $badges[] = [
            'id' => 'streak_king',
            'title' => 'Streak King',
            'description' => 'Belajar 7 hari berturut-turut tanpa absen',
            'info' => $isStreakUnlocked ? 'Tercapai 7 Hari' : ($streakDays > 0 ? "Streak $streakDays Hari" : 'Terkunci'),
            'kategori' => $isStreakUnlocked ? 'Terbuka' : 'Terkunci',
            'status' => $isStreakUnlocked ? 'Terbuka' : 'Terkunci',
            'is_unlocked' => $isStreakUnlocked,
            'current_progress' => min($streakDays, $targetStreak),
            'target_progress' => $targetStreak,
            'progress_text' => "$streakDays / $targetStreak hari",
            'progress_percent' => min(1.0, round($streakDays / $targetStreak, 2)),
            'unlocked_at' => $isStreakUnlocked ? Carbon::now()->subDays(1)->translatedFormat('d M Y') : null,
            'icon_name' => 'local_fire_department',
            'icon_color' => '#DC2626',
            'icon_bg_color' => '#FEE2E2',
            'tentang' => 'Lencana prestisius ini diberikan kepada siswa yang menunjukkan dedikasi konsisten dengan belajar dan masuk aplikasi setiap hari selama 7 hari berturut-turut.',
            'kriteria' => [
                'Masuk ke aplikasi belajar setiap hari',
                'Menyelesaikan minimal 1 sesi materi, listening, kuis, atau game setiap hari',
                'Mempertahankan rekor belajar tanpa putus selama 7 hari berturut-turut',
            ],
            'pencapaian_terkait' => ['early_bird', 'bookworm_scholar', 'score_100', 'game_master'],
        ];

        // 2. Game Master (10.000 Poin di Semua Game)
        $targetGamePoints = 10000;
        $isGameMasterUnlocked = $totalGamePoints >= $targetGamePoints;
        $badges[] = [
            'id' => 'game_master',
            'title' => 'Game Master',
            'description' => 'Memperoleh akumulasi 10.000 poin di game edukasi',
            'info' => $isGameMasterUnlocked ? '10.000+ Poin' : number_format($totalGamePoints, 0, ',', '.').' / 10.000 Poin',
            'kategori' => $isGameMasterUnlocked ? 'Terbuka' : 'Terkunci',
            'status' => $isGameMasterUnlocked ? 'Terbuka' : 'Terkunci',
            'is_unlocked' => $isGameMasterUnlocked,
            'current_progress' => min($totalGamePoints, $targetGamePoints),
            'target_progress' => $targetGamePoints,
            'progress_text' => number_format($totalGamePoints, 0, ',', '.').' / 10.000 pts',
            'progress_percent' => min(1.0, round($totalGamePoints / $targetGamePoints, 2)),
            'unlocked_at' => $isGameMasterUnlocked ? Carbon::now()->translatedFormat('d M Y') : null,
            'icon_name' => 'sports_esports',
            'icon_color' => '#7C3AED',
            'icon_bg_color' => '#EDE9FE',
            'tentang' => 'Lencana kehormatan bagi master game edukasi yang telah menunjukkan ketangkasan kosakata dan deduksi bahasa Inggris hingga menembus 10.000 total poin.',
            'kriteria' => [
                'Bermain mini game Word Fishing atau mode Tebak Serigala',
                'Mengumpulkan skor dari setiap kata dan tantangan yang terjawab benar',
                'Mencapai akumulasi skor permainan minimal 10.000 poin',
            ],
            'pencapaian_terkait' => ['fisherman_elite', 'wolf_detective', 'vocab_master', 'streak_king'],
        ];

        // 3. Top 10 Rombel / Kelas
        $badges[] = [
            'id' => 'top_10',
            'title' => "Top 10 $kelasLengkap",
            'description' => "Masuk peringkat 10 besar $kelasLengkap di kelas",
            'info' => $isTop10 ? Carbon::now()->translatedFormat('M Y') : 'Terkunci',
            'kategori' => $isTop10 ? 'Terbuka' : 'Terkunci',
            'status' => $isTop10 ? 'Terbuka' : 'Terkunci',
            'is_unlocked' => $isTop10,
            'current_progress' => $isTop10 ? 1 : 0,
            'target_progress' => 1,
            'progress_text' => $isTop10 ? 'Peringkat 10 Besar' : 'Belum Masuk 10 Besar',
            'progress_percent' => $isTop10 ? 1.0 : 0.0,
            'unlocked_at' => $isTop10 ? Carbon::now()->translatedFormat('d M Y') : null,
            'icon_name' => 'emoji_events',
            'icon_color' => '#CA8A04',
            'icon_bg_color' => '#FEF9C3',
            'tentang' => "Lencana ini diberikan kepada siswa yang berhasil masuk ke dalam 10 besar peringkat $kelasLengkap berdasarkan hasil kombinasi aktivitas belajar, kuis, dan latihan.",
            'kriteria' => [
                "Menyelesaikan materi dan kuis pada rombel $kelasLengkap",
                'Mendapatkan skor latihan dan kuis di atas rata-rata',
                'Aktif berkontribusi dan mempertahankan posisi 10 besar peringkat kelas',
            ],
            'pencapaian_terkait' => ['academic_star', 'game_master', 'score_100', 'streak_king'],
        ];

        // 4. Early Bird (Belajar Pagi)
        $badges[] = [
            'id' => 'early_bird',
            'title' => 'Early Bird',
            'description' => 'Belajar dan menyelesaikan aktivitas sebelum pukul 07:00 pagi',
            'info' => $hasEarlyBird ? '06:00 WIB' : 'Terkunci',
            'kategori' => $hasEarlyBird ? 'Terbuka' : 'Terkunci',
            'status' => $hasEarlyBird ? 'Terbuka' : 'Terkunci',
            'is_unlocked' => $hasEarlyBird,
            'current_progress' => $hasEarlyBird ? 1 : 0,
            'target_progress' => 1,
            'progress_text' => $hasEarlyBird ? 'Selesai' : '0 / 1 sesi pagi',
            'progress_percent' => $hasEarlyBird ? 1.0 : 0.0,
            'unlocked_at' => $hasEarlyBird ? Carbon::now()->subDays(2)->translatedFormat('d M Y') : null,
            'icon_name' => 'wb_sunny',
            'icon_color' => '#EA580C',
            'icon_bg_color' => '#FFF7ED',
            'tentang' => 'Dianugerahkan kepada siswa rajin yang membiasakan diri bangun pagi dan menyelesaikan aktivitas belajar bahasa Inggris sebelum jam 07:00 pagi.',
            'kriteria' => [
                'Membuka aplikasi di pagi hari (pukul 04:00 - 07:00 WIB)',
                'Menyelesaikan minimal 1 sesi belajar materi atau kuis latihan',
                'Merekam riwayat belajar pagi hari di sistem',
            ],
            'pencapaian_terkait' => ['streak_king', 'bookworm_scholar', 'first_step'],
        ];

        // 5. Score 100 (Nilai Sempurna Kuis)
        $badges[] = [
            'id' => 'score_100',
            'title' => 'Score 100',
            'description' => 'Mendapatkan nilai sempurna 100 pada paket kuis evaluasi',
            'info' => $hasPerfectScore ? 'Skor Sempurna' : 'Terkunci',
            'kategori' => $hasPerfectScore ? 'Terbuka' : 'Terkunci',
            'status' => $hasPerfectScore ? 'Selesai' : 'Terkunci',
            'is_unlocked' => $hasPerfectScore,
            'current_progress' => $hasPerfectScore ? 1 : 0,
            'target_progress' => 1,
            'progress_text' => $hasPerfectScore ? '100 / 100' : 'Skor 100 belum diraih',
            'progress_percent' => $hasPerfectScore ? 1.0 : 0.0,
            'unlocked_at' => $hasPerfectScore ? Carbon::now()->subDays(3)->translatedFormat('d M Y') : null,
            'icon_name' => 'check_circle',
            'icon_color' => '#059669',
            'icon_bg_color' => '#D1FAE5',
            'tentang' => 'Lencana kebanggaan bagi siswa yang berhasil meraih nilai 100 bulat tanpa cela pada salah satu kuis evaluasi bahasa Inggris.',
            'kriteria' => [
                'Mengerjakan paket kuis evaluasi atau room ujian kuis',
                'Menjawab seluruh butir soal dengan tepat dan benar',
                'Memperoleh nilai evaluasi akhir 100',
            ],
            'pencapaian_terkait' => ['quiz_champion', 'academic_star', 'vocab_master'],
        ];

        // 6. Fisherman Elite (Menyelesaikan 5 Level Memancing)
        $targetFishingLevels = 5;
        $isFishermanUnlocked = $completedFishingLevels >= $targetFishingLevels;
        $badges[] = [
            'id' => 'fisherman_elite',
            'title' => 'Fisherman Elite',
            'description' => 'Menuntaskan 5 level akuarium di game Word Fishing',
            'info' => $isFishermanUnlocked ? '5 Level Tuntas' : "$completedFishingLevels / $targetFishingLevels Level",
            'kategori' => $isFishermanUnlocked ? 'Terbuka' : 'Terkunci',
            'status' => $isFishermanUnlocked ? 'Terbuka' : 'Terkunci',
            'is_unlocked' => $isFishermanUnlocked,
            'current_progress' => min($completedFishingLevels, $targetFishingLevels),
            'target_progress' => $targetFishingLevels,
            'progress_text' => "$completedFishingLevels / $targetFishingLevels level",
            'progress_percent' => min(1.0, round($completedFishingLevels / $targetFishingLevels, 2)),
            'unlocked_at' => $isFishermanUnlocked ? Carbon::now()->translatedFormat('d M Y') : null,
            'icon_name' => 'phishing',
            'icon_color' => '#0284C7',
            'icon_bg_color' => '#E0F2FE',
            'tentang' => 'Diberikan kepada penyelam kosakata yang handal dalam menyelesaikan 5 tingkatan level pada permainan interaktif Word Fishing.',
            'kriteria' => [
                'Memancing kata yang sesuai dengan target part of speech',
                'Menghindari rintangan sampah laut dan mempertahankan waktu',
                'Menuntaskan minimal 5 level tantangan memancing',
            ],
            'pencapaian_terkait' => ['game_master', 'wolf_detective', 'vocab_master'],
        ];

        // 7. Quiz Champion (5 Kuis Lulus KKM)
        $targetQuizPassed = 5;
        $isQuizChampUnlocked = $passedQuizCount >= $targetQuizPassed;
        $badges[] = [
            'id' => 'quiz_champion',
            'title' => 'Quiz Champion',
            'description' => 'Menyelesaikan minimal 5 paket kuis tuntas di atas KKM (≥ 75)',
            'info' => $isQuizChampUnlocked ? '5 Kuis Tuntas' : "$passedQuizCount / $targetQuizPassed Kuis",
            'kategori' => $isQuizChampUnlocked ? 'Terbuka' : 'Terkunci',
            'status' => $isQuizChampUnlocked ? 'Terbuka' : 'Terkunci',
            'is_unlocked' => $isQuizChampUnlocked,
            'current_progress' => min($passedQuizCount, $targetQuizPassed),
            'target_progress' => $targetQuizPassed,
            'progress_text' => "$passedQuizCount / $targetQuizPassed paket",
            'progress_percent' => min(1.0, round($passedQuizCount / $targetQuizPassed, 2)),
            'unlocked_at' => $isQuizChampUnlocked ? Carbon::now()->translatedFormat('d M Y') : null,
            'icon_name' => 'psychology',
            'icon_color' => '#D97706',
            'icon_bg_color' => '#FEF3C7',
            'tentang' => 'Lencana bagi siswa tangguh yang konsisten menuntaskan paket soal evaluasi dengan nilai yang melampaui standar kelulusan KKM.',
            'kriteria' => [
                'Mengerjakan berbagai paket kuis materi bahasa Inggris',
                'Mencapai skor minimal 75 pada setiap kuis yang dikerjakan',
                'Menyelesaikan sedikitnya 5 paket kuis evaluasi',
            ],
            'pencapaian_terkait' => ['score_100', 'academic_star', 'first_step'],
        ];

        // 8. Bookworm Scholar (5 Modul Materi)
        $targetMateri = 5;
        $isBookwormUnlocked = $completedMateriCount >= $targetMateri;
        $badges[] = [
            'id' => 'bookworm_scholar',
            'title' => 'Bookworm Scholar',
            'description' => 'Membaca dan menuntaskan 5 modul materi pembelajaran',
            'info' => $isBookwormUnlocked ? '5 Modul Tuntas' : "$completedMateriCount / $targetMateri Modul",
            'kategori' => $isBookwormUnlocked ? 'Terbuka' : 'Terkunci',
            'status' => $isBookwormUnlocked ? 'Terbuka' : 'Terkunci',
            'is_unlocked' => $isBookwormUnlocked,
            'current_progress' => min($completedMateriCount, $targetMateri),
            'target_progress' => $targetMateri,
            'progress_text' => "$completedMateriCount / $targetMateri modul",
            'progress_percent' => min(1.0, round($completedMateriCount / $targetMateri, 2)),
            'unlocked_at' => $isBookwormUnlocked ? Carbon::now()->translatedFormat('d M Y') : null,
            'icon_name' => 'menu_book',
            'icon_color' => '#10B981',
            'icon_bg_color' => '#ECFDF5',
            'tentang' => 'Dianugerahkan kepada siswa yang gemar membaca dan tekun mempelajari rangkuman teori, tata bahasa, dan dialog materi.',
            'kriteria' => [
                'Membaca modul materi pembelajaran yang diterbitkan guru',
                'Mengerjakan latihan pemahaman di akhir materi',
                'Menyelesaikan minimal 5 modul materi pembelajaran',
            ],
            'pencapaian_terkait' => ['audio_maestro', 'vocab_master', 'first_step'],
        ];

        // 9. Audio Maestro (3 Modul Listening)
        $targetListening = 3;
        $isAudioUnlocked = $completedListeningCount >= $targetListening;
        $badges[] = [
            'id' => 'audio_maestro',
            'title' => 'Audio Maestro',
            'description' => 'Menyelesaikan 3 modul audio listening percakapan',
            'info' => $isAudioUnlocked ? '3 Modul Tuntas' : "$completedListeningCount / $targetListening Modul",
            'kategori' => $isAudioUnlocked ? 'Terbuka' : 'Terkunci',
            'status' => $isAudioUnlocked ? 'Terbuka' : 'Terkunci',
            'is_unlocked' => $isAudioUnlocked,
            'current_progress' => min($completedListeningCount, $targetListening),
            'target_progress' => $targetListening,
            'progress_text' => "$completedListeningCount / $targetListening audio",
            'progress_percent' => min(1.0, round($completedListeningCount / $targetListening, 2)),
            'unlocked_at' => $isAudioUnlocked ? Carbon::now()->translatedFormat('d M Y') : null,
            'icon_name' => 'headphones',
            'icon_color' => '#8B5CF6',
            'icon_bg_color' => '#F3E8FF',
            'tentang' => 'Lencana penghargaan atas kemampuan menyimak audio percakapan bahasa Inggris dan menjawab pertanyaan pemahaman dialog.',
            'kriteria' => [
                'Mendengarkan rekaman dialog penutur bahasa Inggris',
                'Mengerjakan latihan soal listening pemahaman konteks',
                'Menuntaskan minimal 3 modul audio listening',
            ],
            'pencapaian_terkait' => ['bookworm_scholar', 'first_step', 'vocab_master'],
        ];

        // 10. Langkah Pertama (First Step)
        $totalAktivitas = $completedMateriCount + $completedListeningCount + $completedQuizCount + $completedFishingLevels;
        $isFirstStepUnlocked = $totalAktivitas >= 1;
        $badges[] = [
            'id' => 'first_step',
            'title' => 'Langkah Pertama',
            'description' => 'Menyelesaikan modul materi, listening, atau kuis pertama kali',
            'info' => $isFirstStepUnlocked ? 'Langkah Awal' : 'Terkunci',
            'kategori' => $isFirstStepUnlocked ? 'Terbuka' : 'Terkunci',
            'status' => $isFirstStepUnlocked ? 'Selesai' : 'Terkunci',
            'is_unlocked' => $isFirstStepUnlocked,
            'current_progress' => min(1, $totalAktivitas),
            'target_progress' => 1,
            'progress_text' => $isFirstStepUnlocked ? 'Selesai' : '0 / 1 aktivitas',
            'progress_percent' => $isFirstStepUnlocked ? 1.0 : 0.0,
            'unlocked_at' => $isFirstStepUnlocked ? Carbon::now()->translatedFormat('d M Y') : null,
            'icon_name' => 'flag',
            'icon_color' => '#2563EB',
            'icon_bg_color' => '#DBEAFE',
            'tentang' => 'Langkah awal dari perjalanan belajar! Diperoleh ketika siswa berhasil menyelesaikan aktivitas belajar perdananya.',
            'kriteria' => [
                'Memilih salah satu materi, latihan audio listening, atau kuis',
                'Menyelesaikan aktivitas pembelajaran hingga akhir',
                'Mendapatkan catatan nilai dan XP pertama di aplikasi',
            ],
            'pencapaian_terkait' => ['bookworm_scholar', 'audio_maestro', 'streak_king'],
        ];

        // 11. Wolf Detective (Mode Game Wolf vs Sheep)
        $badges[] = [
            'id' => 'wolf_detective',
            'title' => 'Wolf Detective',
            'description' => 'Bermain mode tebak kata dan deduksi di Wolf vs Sheep',
            'info' => $hasWolfGame ? 'Aktif Bermain' : 'Terkunci',
            'kategori' => $hasWolfGame ? 'Terbuka' : 'Terkunci',
            'status' => $hasWolfGame ? 'Terbuka' : 'Terkunci',
            'is_unlocked' => $hasWolfGame,
            'current_progress' => $hasWolfGame ? 1 : 0,
            'target_progress' => 1,
            'progress_text' => $hasWolfGame ? 'Selesai' : '0 / 1 permainan',
            'progress_percent' => $hasWolfGame ? 1.0 : 0.0,
            'unlocked_at' => $hasWolfGame ? Carbon::now()->translatedFormat('d M Y') : null,
            'icon_name' => 'visibility',
            'icon_color' => '#0D9488',
            'icon_bg_color' => '#CCFBF1',
            'tentang' => 'Lencana khusus bagi detektif kata yang berani mengasah nalar logika dan kemampuan bahasa dalam game Wolf vs Sheep.',
            'kriteria' => [
                'Membuka dan bermain dalam mode permainan Tebak Serigala (Wolf vs Sheep)',
                'Menganalisis petunjuk kartu kosakata dan menebak peran dengan benar',
                'Menyelesaikan minimal 1 putaran permainan',
            ],
            'pencapaian_terkait' => ['game_master', 'fisherman_elite', 'vocab_master'],
        ];

        // 12. Vocabulary Master (30 Kosakata Tuntas)
        $targetVocab = 30;
        $isVocabUnlocked = $totalVocabLearned >= $targetVocab;
        $badges[] = [
            'id' => 'vocab_master',
            'title' => 'Vocabulary Master',
            'description' => 'Menguasai minimal 30 butir kosakata dari modul & kuis',
            'info' => $isVocabUnlocked ? '30+ Kosakata' : "$totalVocabLearned / $targetVocab Kata",
            'kategori' => $isVocabUnlocked ? 'Terbuka' : 'Terkunci',
            'status' => $isVocabUnlocked ? 'Terbuka' : 'Terkunci',
            'is_unlocked' => $isVocabUnlocked,
            'current_progress' => min($totalVocabLearned, $targetVocab),
            'target_progress' => $targetVocab,
            'progress_text' => "$totalVocabLearned / $targetVocab kata",
            'progress_percent' => min(1.0, round($totalVocabLearned / $targetVocab, 2)),
            'unlocked_at' => $isVocabUnlocked ? Carbon::now()->translatedFormat('d M Y') : null,
            'icon_name' => 'auto_stories',
            'icon_color' => '#D97706',
            'icon_bg_color' => '#FEF3C7',
            'tentang' => 'Diberikan kepada siswa yang berhasil memperkaya perbendaharaan kosakata bahasa Inggris melalui latihan dan evaluasi.',
            'kriteria' => [
                'Menjawab butir soal kosakata dengan benar di latihan materi dan kuis',
                'Menuntaskan level permainan penangkapan kata pada Word Fishing',
                'Menguasai sedikitnya 30 butir kosakata bahasa Inggris',
            ],
            'pencapaian_terkait' => ['bookworm_scholar', 'game_master', 'score_100'],
        ];

        // 13. Academic Star (Kategori Langka: Rerata Nilai >= 80)
        $targetAvg = 80;
        $isAcademicStarUnlocked = $completedQuizCount >= 2 && $avgQuizScore >= $targetAvg;
        $badges[] = [
            'id' => 'academic_star',
            'title' => 'Academic Star',
            'description' => 'Mempertahankan rerata nilai evaluasi kuis & materi ≥ 80',
            'info' => $isAcademicStarUnlocked ? 'Rerata '.round($avgQuizScore) : ($completedQuizCount > 0 ? 'Rerata '.round($avgQuizScore) : 'Terkunci'),
            'kategori' => 'Langka',
            'status' => $isAcademicStarUnlocked ? 'Langka' : 'Terkunci',
            'is_unlocked' => $isAcademicStarUnlocked,
            'current_progress' => min(round($avgQuizScore), $targetAvg),
            'target_progress' => $targetAvg,
            'progress_text' => round($avgQuizScore).' / 80 poin rerata',
            'progress_percent' => min(1.0, round($avgQuizScore / $targetAvg, 2)),
            'unlocked_at' => $isAcademicStarUnlocked ? Carbon::now()->translatedFormat('d M Y') : null,
            'icon_name' => 'star',
            'icon_color' => '#7C3AED',
            'icon_bg_color' => '#EDE9FE',
            'tentang' => 'Lencana Emas Langka bagi siswa berprestasi yang mampu konsisten menjaga performa akademik ujian di atas standar mutu 80.',
            'kriteria' => [
                'Mengerjakan minimal 2 paket evaluasi kuis secara penuh',
                'Mempertahankan nilai rata-rata keseluruhan minimal 80',
                'Tidak memiliki catatan nilai di bawah batas remedial',
            ],
            'pencapaian_terkait' => ['score_100', 'quiz_champion', 'top_10'],
        ];

        // 7. HITUNG RINGKASAN PROGRESS KESELURUHAN
        $totalLencana = count($badges);
        $totalTerbuka = collect($badges)->where('is_unlocked', true)->count();
        $totalTerkunci = $totalLencana - $totalTerbuka;
        $selesai = $totalTerbuka;
        $persentase = $totalLencana > 0 ? round(($totalTerbuka / $totalLencana) * 100) : 0;

        return response()->json([
            'status' => 'success',
            'message' => 'Data pencapaian siswa berhasil dimuat.',
            'summary' => [
                'total_terbuka' => $totalTerbuka,
                'total_terkunci' => $totalTerkunci,
                'selesai' => $selesai,
                'total_lencana' => $totalLencana,
                'persentase' => $persentase,
            ],
            'data' => $badges,
        ], 200);
    }
}
