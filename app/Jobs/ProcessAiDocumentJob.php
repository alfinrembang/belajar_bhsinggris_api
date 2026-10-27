<?php

namespace App\Jobs;

use App\Models\AiImport;
use App\Services\Ai\DocumentParserService;
use App\Services\Ai\GeminiAiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class ProcessAiDocumentJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah percobaan maksimal jika terjadi kegagalan jaringan atau rate-limit.
     */
    public int $tries = 3;

    /**
     * Jeda waktu antar percobaan ulang (dalam detik).
     *
     * @var array<int, int>
     */
    public array $backoff = [10, 30, 60];

    /**
     * Waktu timeout eksekusi job (dalam detik).
     */
    public int $timeout = 180;

    /**
     * Create a new job instance.
     */
    public function __construct(public AiImport $aiImport) {}

    /**
     * Execute the job.
     */
    public function handle(DocumentParserService $documentParser, GeminiAiService $geminiAi): void
    {
        $this->aiImport->refresh();

        if ($this->aiImport->isCompleted()) {
            Log::info("ProcessAiDocumentJob: Dokumen #{$this->aiImport->id} sudah pernah selesai diproses, melewati job.");

            return;
        }

        $this->aiImport->update([
            'status' => 'processing',
            'error_message' => null,
        ]);

        Log::info("ProcessAiDocumentJob: Memulai pemrosesan dokumen #{$this->aiImport->id} ({$this->aiImport->nama_file_asli})");

        try {
            $filePath = $this->resolveFilePath($this->aiImport->file_path);

            if (! file_exists($filePath)) {
                throw new RuntimeException("File fisik dokumen tidak ditemukan pada server: {$filePath}");
            }

            // 1. Ekstraksi teks dari file dokumen (PPTX/DOCX/PDF/TXT)
            $extractedText = $documentParser->parse($filePath, $this->aiImport->format_file);

            if (trim($extractedText) === '') {
                throw new RuntimeException("Dokumen '{$this->aiImport->nama_file_asli}' kosong atau tidak memuat karakter teks yang dapat diekstrak.");
            }

            // 2. Kirim ke Gemini AI Service untuk diolah menjadi JSON terstruktur
            $structuredJson = $geminiAi->extractContent($extractedText, $this->aiImport->tipe_target);

            // 3. Simpan hasil ekstraksi dan perbarui status
            $this->aiImport->update([
                'status' => 'completed',
                'hasil_ekstraksi_json' => $structuredJson,
                'error_message' => null,
            ]);

            Log::info("ProcessAiDocumentJob: Berhasil mengekstrak dokumen #{$this->aiImport->id} ({$this->aiImport->nama_file_asli})");
        } catch (Throwable $e) {
            Log::error("ProcessAiDocumentJob Gagal pada dokumen #{$this->aiImport->id}: ".$e->getMessage());

            $this->aiImport->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Tangani kegagalan permanen setelah semua percobaan (retries) habis.
     */
    public function failed(?Throwable $exception): void
    {
        $this->aiImport->refresh();

        $this->aiImport->update([
            'status' => 'failed',
            'error_message' => $exception?->getMessage() ?? 'Gagal memproses dokumen setelah beberapa kali percobaan.',
        ]);

        Log::error("ProcessAiDocumentJob: Gagal permanen pada dokumen #{$this->aiImport->id}. Error: ".$exception?->getMessage());
    }

    /**
     * Menemukan path absolut file dokumen pada storage.
     */
    protected function resolveFilePath(string $path): string
    {
        $localPath = Storage::disk('local')->path($path);
        if (file_exists($localPath)) {
            return $localPath;
        }

        $appPath = storage_path('app/'.$path);
        if (file_exists($appPath)) {
            return $appPath;
        }

        if (file_exists($path)) {
            return $path;
        }

        return $localPath;
    }
}
