<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\ConfirmAiMateriRequest;
use App\Http\Requests\Guru\ConfirmAiQuizRequest;
use App\Http\Requests\Guru\UploadAiDocumentRequest;
use App\Jobs\ProcessAiDocumentJob;
use App\Models\AiImport;
use App\Models\KategoriMateri;
use App\Models\Materi;
use App\Models\MateriSoal;
use App\Models\Quiz;
use App\Models\QuizSoal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AiImportController extends Controller
{
    /**
     * Upload file dokumen pembelajaran untuk diproses otomatis via Background Job & Gemini AI.
     */
    public function upload(UploadAiDocumentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $uploadedFile = $request->file('file');
        $storedPath = $uploadedFile->store('dokumen_ai', 'local');

        $aiImport = AiImport::create([
            'user_id' => auth()->id(),
            'tipe_target' => $validated['tipe_target'],
            'nama_file_asli' => $uploadedFile->getClientOriginalName(),
            'file_path' => $storedPath,
            'format_file' => strtolower($uploadedFile->getClientOriginalExtension()),
            'ukuran_file' => $uploadedFile->getSize(),
            'status' => 'pending',
        ]);

        // Kirim ke background queue (Redis)
        ProcessAiDocumentJob::dispatch($aiImport);

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diunggah dan sedang diproses AI di latar belakang.',
            'import_id' => $aiImport->id,
            'check_url' => route('guru.ai-import.status', $aiImport->id),
        ]);
    }

    /**
     * Endpoint polling status proses AI untuk antarmuka real-time di browser.
     */
    public function status(AiImport $aiImport): JsonResponse
    {
        $user = auth()->user();
        if ($user && $user->role === 'guru' && $aiImport->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk memantau dokumen ini.');
        }

        return response()->json([
            'id' => $aiImport->id,
            'status' => $aiImport->status,
            'error_message' => $aiImport->error_message,
            'has_result' => ! empty($aiImport->hasil_ekstraksi_json),
            'review_url' => route('guru.ai-import.review', $aiImport->id),
        ]);
    }

    /**
     * Halaman review dan kurasi manual hasil parsing AI sebelum dipublikasikan.
     */
    public function review(AiImport $aiImport): View|RedirectResponse
    {
        $user = auth()->user();
        if ($user && $user->role === 'guru' && $aiImport->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat kurasi dokumen ini.');
        }

        if (! $aiImport->isCompleted()) {
            return redirect()
                ->route($aiImport->tipe_target === 'quiz' ? 'guru.quiz.index' : 'guru.materi.index')
                ->with('error', 'Dokumen belum selesai diproses oleh AI atau mengalami kendala pemrosesan.');
        }

        $hasil = $aiImport->hasil_ekstraksi_json ?? [];
        $kategoriList = KategoriMateri::where('is_aktif', true)->orderBy('urutan')->get();
        $kelasDiampu = $user->kelas_diampu ?? [];

        return view('guru.ai_import.review', compact('aiImport', 'hasil', 'kategoriList', 'kelasDiampu'));
    }

    /**
     * Konfirmasi akhir dan simpan hasil AI menjadi record Materi resmi di database.
     */
    public function confirmMateri(ConfirmAiMateriRequest $request, AiImport $aiImport): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $user, $request) {
            $materi = Materi::create([
                'user_id' => $user->id,
                'judul' => $validated['judul'],
                'kategori' => $validated['kategori'],
                'tingkat_kelas' => $validated['tingkat_kelas'],
                'target_rombel' => $validated['target_rombel'] ?? null,
                'deskripsi_singkat' => $validated['deskripsi_singkat'],
                'penjelasan' => $validated['penjelasan'],
                'contoh_teks' => $validated['contoh_teks'] ?? null,
                'xp_reward' => $validated['xp_reward'] ?? 50,
                'status' => $validated['status'],
            ]);

            $soalsInput = $request->input('soals', []);
            if (is_array($soalsInput)) {
                foreach ($soalsInput as $item) {
                    if (empty($item['pertanyaan'])) {
                        continue;
                    }

                    MateriSoal::create([
                        'materi_id' => $materi->id,
                        'tipe_soal' => 'pilgan',
                        'pertanyaan' => trim((string) $item['pertanyaan']),
                        'pilihan_a' => trim((string) ($item['pilihan_a'] ?? '-')),
                        'pilihan_b' => trim((string) ($item['pilihan_b'] ?? '-')),
                        'pilihan_c' => trim((string) ($item['pilihan_c'] ?? '-')),
                        'pilihan_d' => trim((string) ($item['pilihan_d'] ?? '-')),
                        'kunci_jawaban' => strtoupper(trim((string) ($item['kunci_jawaban'] ?? 'A'))),
                        'pembahasan' => isset($item['pembahasan']) && trim((string) $item['pembahasan']) !== '' ? trim((string) $item['pembahasan']) : null,
                    ]);
                }
            }
        });

        return redirect()
            ->route('guru.materi.index')
            ->with('success', "Modul materi '{$validated['judul']}' berhasil diterbitkan dari hasil ekstraksi AI!");
    }

    /**
     * Konfirmasi akhir dan simpan hasil AI menjadi record Kuis resmi di database.
     */
    public function confirmQuiz(ConfirmAiQuizRequest $request, AiImport $aiImport): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $user, $request) {
            $quiz = Quiz::create([
                'user_id' => $user->id,
                'judul' => $validated['judul'],
                'kategori' => $validated['kategori'],
                'tingkat_kelas' => $validated['tingkat_kelas'],
                'target_rombel' => $validated['target_rombel'] ?? null,
                'kode_room' => ! empty($validated['kode_room']) ? strtoupper(trim($validated['kode_room'])) : Quiz::generateUniqueRoomCode(),
                'durasi_menit' => (int) $validated['durasi_menit'],
                'deskripsi' => $validated['deskripsi'],
                'xp_reward' => $validated['xp_reward'] ?? 60,
                'status' => $validated['status'],
            ]);

            $soalsInput = $request->input('soals', []);
            if (is_array($soalsInput)) {
                $nomor = 1;
                foreach ($soalsInput as $item) {
                    if (empty($item['pertanyaan'])) {
                        continue;
                    }

                    $tipeSoal = ($item['tipe_soal'] ?? 'multiple_choice') === 'reading' ? 'reading' : 'pilgan';

                    QuizSoal::create([
                        'quiz_id' => $quiz->id,
                        'nomor_soal' => $nomor++,
                        'tipe_soal' => $tipeSoal,
                        'reading_passage' => ! empty($item['reading_passage']) ? trim((string) $item['reading_passage']) : null,
                        'pertanyaan' => trim((string) $item['pertanyaan']),
                        'pilihan_a' => trim((string) ($item['pilihan_a'] ?? '-')),
                        'pilihan_b' => trim((string) ($item['pilihan_b'] ?? '-')),
                        'pilihan_c' => trim((string) ($item['pilihan_c'] ?? '-')),
                        'pilihan_d' => trim((string) ($item['pilihan_d'] ?? '-')),
                        'kunci_jawaban' => strtoupper(trim((string) ($item['kunci_jawaban'] ?? 'A'))),
                        'pembahasan' => isset($item['pembahasan']) && trim((string) $item['pembahasan']) !== '' ? trim((string) $item['pembahasan']) : null,
                    ]);
                }
            }
        });

        return redirect()
            ->route('guru.quiz.index')
            ->with('success', "Paket kuis '{$validated['judul']}' berhasil diterbitkan dari hasil ekstraksi AI!");
    }
}
