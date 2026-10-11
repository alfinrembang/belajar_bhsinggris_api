<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\StoreQuizRequest;
use App\Http\Requests\Guru\UpdateQuizRequest;
use App\Models\Quiz;
use App\Models\QuizSoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class QuizController extends Controller
{
    /**
     * Menampilkan daftar seluruh paket kuis evaluasi.
     */
    public function index(Request $request): View
    {
        $query = Quiz::withCount('soals')->latest();

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter Kelas
        if ($request->filled('kelas')) {
            $query->where(function ($q) use ($request) {
                $q->where('tingkat_kelas', $request->kelas)
                    ->orWhere('tingkat_kelas', 'Semua Kelas');
            });
        }

        // Pencarian Judul atau Kode Room
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('kode_room', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $quizzes = $query->paginate(10)->withQueryString();

        // Statistik Ringkas
        $totalQuiz = Quiz::count();
        $totalAktif = Quiz::where('status', 'aktif')->count();
        $totalSoal = QuizSoal::count();

        return view('guru.bank_soal_kuis.index', compact('quizzes', 'totalQuiz', 'totalAktif', 'totalSoal'));
    }

    /**
     * Menampilkan formulir buat paket kuis baru.
     */
    public function create(): View
    {
        $defaultRoomCode = Quiz::generateUniqueRoomCode();

        return view('guru.bank_soal_kuis.create', compact('defaultRoomCode'));
    }

    /**
     * Menyimpan paket kuis baru beserta seluruh butir soalnya secara atomik.
     */
    public function store(StoreQuizRequest $request): RedirectResponse
    {
        $newFiles = [];

        try {
            DB::transaction(function () use ($request, &$newFiles) {
                $data = $request->only([
                    'judul',
                    'kategori',
                    'tingkat_kelas',
                    'kode_room',
                    'durasi_menit',
                    'deskripsi',
                    'xp_reward',
                    'status',
                ]);

                $data['xp_reward'] = $data['xp_reward'] ?? 60;
                $data['kode_room'] = ! empty($data['kode_room'])
                    ? strtoupper(trim($data['kode_room']))
                    : Quiz::generateUniqueRoomCode();

                $quiz = Quiz::create($data);

                $this->syncSoals($request, $quiz, false, $newFiles);
            });
        } catch (\Throwable $e) {
            foreach ($newFiles as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
            throw $e;
        }

        return redirect()->route('guru.quiz.index')->with('success', 'Paket kuis baru berhasil dibuat dan diterbitkan!');
    }

    /**
     * Menampilkan preview detail kuis beserta riwayat nilai siswa yang telah mengerjakan.
     */
    public function show(Quiz $quiz): View
    {
        $quiz->load(['soals', 'progresSiswa.siswa']);

        return view('guru.bank_soal_kuis.show', compact('quiz'));
    }

    /**
     * Menampilkan formulir edit kuis.
     */
    public function edit(Quiz $quiz): View
    {
        $quiz->load('soals');

        return view('guru.bank_soal_kuis.edit', compact('quiz'));
    }

    /**
     * Memperbarui paket kuis dan butir soalnya secara atomik.
     */
    public function update(UpdateQuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $newFiles = [];
        $filesToDelete = [];

        try {
            DB::transaction(function () use ($request, $quiz, &$newFiles, &$filesToDelete) {
                $data = $request->only([
                    'judul',
                    'kategori',
                    'tingkat_kelas',
                    'kode_room',
                    'durasi_menit',
                    'deskripsi',
                    'xp_reward',
                    'status',
                ]);

                $data['kode_room'] = strtoupper(trim($data['kode_room']));
                $quiz->update($data);

                $this->syncSoals($request, $quiz, true, $newFiles, $filesToDelete);
            });

            // Bersihkan file lama setelah transaksi sukses
            foreach ($filesToDelete as $oldFile) {
                if (Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
        } catch (\Throwable $e) {
            foreach ($newFiles as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
            throw $e;
        }

        return redirect()->route('guru.quiz.index')->with('success', 'Perubahan paket kuis berhasil disimpan!');
    }

    /**
     * Menghapus paket kuis beserta seluruh latihan soal dan file audio fisiknya.
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        $filesToDelete = [];

        foreach ($quiz->soals as $soal) {
            if ($soal->audio_file) {
                $filesToDelete[] = $soal->audio_file;
            }
        }

        DB::transaction(function () use ($quiz) {
            $quiz->soals()->delete();
            $quiz->progresSiswa()->delete();
            $quiz->delete();
        });

        foreach ($filesToDelete as $filePath) {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        return redirect()->route('guru.quiz.index')->with('success', 'Paket kuis berhasil dihapus.');
    }

    /**
     * Helper untuk memproses butir soal kuis (Pilgan, Reading, Listening).
     *
     * @param  array<int, string>  $newFiles
     * @param  array<int, string>  $filesToDelete
     */
    private function syncSoals(
        Request $request,
        Quiz $quiz,
        bool $isUpdate = false,
        array &$newFiles = [],
        array &$filesToDelete = []
    ): void {
        $oldAudioPaths = $isUpdate ? $quiz->soals->pluck('audio_file')->filter()->values()->all() : [];
        $retainedAudioPaths = [];

        if ($isUpdate) {
            $quiz->soals()->delete();
        }

        $soalsInput = $request->input('soals', []);

        if (is_array($soalsInput)) {
            $nomor = 1;
            foreach ($soalsInput as $index => $item) {
                $hasNewAudio = $request->hasFile("soals.{$index}.audio_file");
                $hasExistingAudio = ! empty($item['existing_audio_file'])
                    && in_array($item['existing_audio_file'], $oldAudioPaths, true);

                if (empty($item['pertanyaan']) && ! $hasNewAudio && ! $hasExistingAudio) {
                    continue;
                }

                $tipeSoal = $item['tipe_soal'] ?? 'pilgan';
                $audioFilePath = null;

                if ($tipeSoal === 'listening') {
                    if ($hasNewAudio) {
                        $audioFilePath = $request->file("soals.{$index}.audio_file")->store('quiz/audio', 'public');
                        $newFiles[] = $audioFilePath;
                    } elseif ($hasExistingAudio) {
                        $audioFilePath = $item['existing_audio_file'];
                    }

                    if ($audioFilePath) {
                        $retainedAudioPaths[] = $audioFilePath;
                    }
                }

                QuizSoal::create([
                    'quiz_id' => $quiz->id,
                    'nomor_soal' => $nomor++,
                    'tipe_soal' => $tipeSoal,
                    'reading_passage' => $tipeSoal === 'reading' ? ($item['reading_passage'] ?? null) : null,
                    'audio_file' => $audioFilePath,
                    'audio_title' => $item['audio_title'] ?? null,
                    'audio_duration' => $item['audio_duration'] ?? '01:30',
                    'pertanyaan' => $item['pertanyaan'] ?? 'Pertanyaan kuis',
                    'pilihan_a' => $item['pilihan_a'] ?? '-',
                    'pilihan_b' => $item['pilihan_b'] ?? '-',
                    'pilihan_c' => $item['pilihan_c'] ?? '-',
                    'pilihan_d' => $item['pilihan_d'] ?? '-',
                    'kunci_jawaban' => strtoupper($item['kunci_jawaban'] ?? 'A'),
                    'pembahasan' => $item['pembahasan'] ?? null,
                ]);
            }
        }

        if ($isUpdate) {
            foreach ($oldAudioPaths as $oldPath) {
                if (! in_array($oldPath, $retainedAudioPaths, true)) {
                    $filesToDelete[] = $oldPath;
                }
            }
        }
    }
}
