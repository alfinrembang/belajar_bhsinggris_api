<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\SiswaQuizProgres;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizApiController extends Controller
{
    /**
     * Mengambil daftar paket kuis aktif untuk aplikasi Flutter siswa.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Quiz::where('status', 'aktif')
            ->withCount('soals')
            ->latest('id');

        // Filter Kategori
        if ($request->filled('kategori') && ! in_array(strtolower($request->kategori), ['all', 'semua', ''])) {
            $query->where('kategori', ucfirst(strtolower($request->kategori)));
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

        // Peta Progres Siswa jika siswa_id dikirimkan
        $siswaId = $request->input('siswa_id');
        $progresMap = [];
        if ($siswaId) {
            $progresList = SiswaQuizProgres::where('siswa_id', $siswaId)->get();
            foreach ($progresList as $p) {
                $progresMap[$p->quiz_id] = [
                    'skor' => $p->skor,
                    'status' => $p->status,
                    'completed_at' => $p->completed_at ? $p->completed_at->format('d M Y H:i') : null,
                ];
            }
        }

        $quizzes = $query->get()->map(function ($item) use ($progresMap) {
            $progres = $progresMap[$item->id] ?? null;
            $isCompleted = $progres && $progres['status'] === 'selesai';

            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'kategori' => $item->kategori,
                'kategori_inisial' => $item->kategori_inisial,
                'tingkat_kelas' => $item->tingkat_kelas,
                'kode_room' => $item->kode_room,
                'durasi_menit' => $item->durasi_menit,
                'deskripsi' => $item->deskripsi,
                'xp_reward' => $item->xp_reward,
                'jumlah_soal' => $item->soals_count,
                'is_completed' => $isCompleted,
                'skor_terakhir' => $progres ? $progres['skor'] : null,
                'completed_at' => $progres ? $progres['completed_at'] : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar paket kuis berhasil dimuat',
            'data' => $quizzes,
        ]);
    }

    /**
     * Bergabung ke room kuis menggunakan kode unik 6 digit.
     */
    public function joinRoom(Request $request): JsonResponse
    {
        $request->validate([
            'kode_room' => 'required|string|max:20',
        ], [
            'kode_room.required' => 'Kode room wajib dimasukkan!',
        ]);

        $code = strtoupper(trim($request->kode_room));

        $quiz = Quiz::where('kode_room', $code)
            ->where('status', 'aktif')
            ->withCount('soals')
            ->first();

        if (! $quiz) {
            return response()->json([
                'status' => 'error',
                'message' => "Kode room '{$code}' tidak ditemukan atau kuis belum diaktifkan oleh guru.",
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Room kuis berhasil ditemukan!',
            'data' => [
                'id' => $quiz->id,
                'judul' => $quiz->judul,
                'kategori' => $quiz->kategori,
                'tingkat_kelas' => $quiz->tingkat_kelas,
                'kode_room' => $quiz->kode_room,
                'durasi_menit' => $quiz->durasi_menit,
                'deskripsi' => $quiz->deskripsi,
                'xp_reward' => $quiz->xp_reward,
                'jumlah_soal' => $quiz->soals_count,
            ],
        ]);
    }

    /**
     * Mengambil detail paket kuis beserta butir soal (tanpa kunci jawaban saat pengerjaan).
     */
    public function show(int $id): JsonResponse
    {
        $quiz = Quiz::with('soals')->where('status', 'aktif')->find($id);

        if (! $quiz) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paket kuis tidak ditemukan atau belum aktif!',
            ], 404);
        }

        $soals = $quiz->soals->map(function ($s) {
            return [
                'id' => $s->id,
                'nomor_soal' => $s->nomor_soal,
                'tipe_soal' => $s->tipe_soal,
                'reading_passage' => $s->reading_passage,
                'audio_url' => $s->audio_url,
                'audio_title' => $s->audio_title ?: 'Audio Soal Listening',
                'audio_duration' => $s->audio_duration ?: '01:30',
                'pertanyaan' => $s->pertanyaan,
                'pilihan_a' => $s->pilihan_a,
                'pilihan_b' => $s->pilihan_b,
                'pilihan_c' => $s->pilihan_c,
                'pilihan_d' => $s->pilihan_d,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Detail paket kuis berhasil dimuat',
            'data' => [
                'id' => $quiz->id,
                'judul' => $quiz->judul,
                'kategori' => $quiz->kategori,
                'tingkat_kelas' => $quiz->tingkat_kelas,
                'kode_room' => $quiz->kode_room,
                'durasi_menit' => $quiz->durasi_menit,
                'deskripsi' => $quiz->deskripsi,
                'xp_reward' => $quiz->xp_reward,
                'jumlah_soal' => $quiz->soals->count(),
                'soals' => $soals,
            ],
        ]);
    }

    /**
     * Memproses pengiriman lembar jawaban kuis siswa secara aman (Anti-Cheat Server-Side Evaluation).
     */
    public function submit(Request $request, int $id): JsonResponse
    {
        $quiz = Quiz::with('soals')->find($id);

        if (! $quiz) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paket kuis tidak ditemukan!',
            ], 404);
        }

        $request->validate([
            'siswa_id' => 'nullable|integer',
            'jawaban' => 'required|array',
            'waktu_pengerjaan_detik' => 'nullable|integer|min:0',
        ], [
            'jawaban.required' => 'Lembar jawaban tidak boleh kosong!',
        ]);

        $totalSoal = $quiz->soals->count();

        if ($totalSoal === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paket kuis ini belum memiliki butir pertanyaan!',
            ], 422);
        }

        $userAnswers = $request->input('jawaban', []);
        $answeredCount = 0;

        foreach ($quiz->soals as $soal) {
            $userAns = $userAnswers[$soal->id] ?? $userAnswers[(string) $soal->id] ?? null;
            if ($userAns !== null && trim((string) $userAns) !== '') {
                $answeredCount++;
            }
        }

        // Anti-Cheat: Seluruh butir soal kuis wajib dijawab sebelum disubmit
        if ($answeredCount < $totalSoal) {
            return response()->json([
                'status' => 'error',
                'message' => "Seluruh {$totalSoal} butir soal kuis wajib dijawab sebelum dikirimkan!",
            ], 422);
        }

        // Hitung skor dan evaluasi di sisi server
        $jumlahBenar = 0;
        $reviewDetails = [];

        foreach ($quiz->soals as $soal) {
            $userAns = strtoupper(trim((string) ($userAnswers[$soal->id] ?? $userAnswers[(string) $soal->id] ?? '')));
            $kunci = strtoupper(trim((string) $soal->kunci_jawaban));
            $isBenar = ($userAns !== '' && $userAns === $kunci);

            if ($isBenar) {
                $jumlahBenar++;
            }

            $reviewDetails[] = [
                'soal_id' => $soal->id,
                'nomor_soal' => $soal->nomor_soal,
                'tipe_soal' => $soal->tipe_soal,
                'pertanyaan' => $soal->pertanyaan,
                'jawaban_siswa' => $userAns,
                'kunci_jawaban' => $kunci,
                'is_benar' => $isBenar,
                'pembahasan' => $soal->pembahasan,
            ];
        }

        $jumlahSalah = $totalSoal - $jumlahBenar;
        $skor = (int) round(($jumlahBenar / $totalSoal) * 100);

        // Anti-Cheat: Hitung reward XP berdasarkan skor pencapaian
        $baseXp = (int) ($quiz->xp_reward ?? 60);
        $xpDidapat = (int) round($baseXp * max(0.2, $skor / 100));
        $xpDidapat = max(10, $xpDidapat);

        $siswaId = $request->input('siswa_id');
        $validSiswa = $siswaId ? Siswa::find($siswaId) : null;
        $effectiveSiswaId = $validSiswa?->id;

        if ($effectiveSiswaId) {
            DB::transaction(function () use ($effectiveSiswaId, $quiz, $skor, $jumlahBenar, $totalSoal, $xpDidapat, $reviewDetails, $request) {
                SiswaQuizProgres::updateOrCreate(
                    [
                        'siswa_id' => $effectiveSiswaId,
                        'quiz_id' => $quiz->id,
                    ],
                    [
                        'skor' => $skor,
                        'jumlah_benar' => $jumlahBenar,
                        'total_soal' => $totalSoal,
                        'xp_didapat' => $xpDidapat,
                        'jawaban_detail' => $reviewDetails,
                        'waktu_pengerjaan_detik' => (int) $request->input('waktu_pengerjaan_detik', 0),
                        'status' => 'selesai',
                        'completed_at' => now(),
                    ]
                );
            });
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Luar biasa! Jawaban kuis berhasil dikirimkan.',
            'data' => [
                'quiz_id' => $quiz->id,
                'judul' => $quiz->judul,
                'skor' => $skor,
                'jumlah_benar' => $jumlahBenar,
                'jumlah_salah' => $jumlahSalah,
                'total_soal' => $totalSoal,
                'xp_didapat' => $xpDidapat,
                'waktu_pengerjaan_detik' => (int) $request->input('waktu_pengerjaan_detik', 0),
                'review' => $reviewDetails,
            ],
        ]);
    }

    /**
     * Mengambil riwayat pengerjaan kuis siswa.
     */
    public function progres(Request $request): JsonResponse
    {
        $siswaId = $request->input('siswa_id');

        if (! $siswaId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter siswa_id wajib disertakan!',
            ], 422);
        }

        $riwayat = SiswaQuizProgres::with('quiz')
            ->where('siswa_id', $siswaId)
            ->latest('completed_at')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'quiz_id' => $item->quiz_id,
                    'judul_quiz' => $item->quiz?->judul ?? 'Kuis',
                    'kategori' => $item->quiz?->kategori ?? 'General',
                    'skor' => $item->skor,
                    'jumlah_benar' => $item->jumlah_benar,
                    'total_soal' => $item->total_soal,
                    'xp_didapat' => $item->xp_didapat,
                    'status' => $item->status,
                    'waktu_pengerjaan_detik' => $item->waktu_pengerjaan_detik,
                    'completed_at' => $item->completed_at ? $item->completed_at->format('d M Y H:i') : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat kuis siswa berhasil dimuat',
            'data' => $riwayat,
        ]);
    }
}
