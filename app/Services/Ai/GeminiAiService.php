<?php

namespace App\Services\Ai;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use JsonException;
use RuntimeException;

class GeminiAiService
{
    protected string $apiKey;

    protected string $model;

    protected int $timeout;

    public function __construct(?string $apiKey = null, ?string $model = null, ?int $timeout = null)
    {
        $this->apiKey = $apiKey ?? (string) Config::get('services.gemini.api_key', '');
        $this->model = $model ?? (string) Config::get('services.gemini.model', 'gemini-3.5-flash');
        $this->timeout = $timeout ?? (int) Config::get('services.gemini.timeout', 60);
    }

    /**
     * Ekstrak isi teks dokumen menjadi struktur JSON terstandarisasi untuk Materi atau Kuis.
     *
     * @param  string  $documentText  Teks mentah hasil ekstraksi dokumen (PPTX/DOCX/PDF).
     * @param  string  $targetType  Tipe target ekstraksi ('materi' atau 'quiz').
     * @return array<string, mixed> Struktur data hasil ekstraksi AI.
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function extractContent(string $documentText, string $targetType = 'materi'): array
    {
        if (trim($documentText) === '') {
            throw new InvalidArgumentException('Teks dokumen kosong. Tidak ada konten yang dapat diproses oleh AI.');
        }

        if (empty($this->apiKey)) {
            throw new RuntimeException('GEMINI_API_KEY belum dikonfigurasi di file .env.');
        }

        $prompt = match ($targetType) {
            'quiz' => $this->buildQuizPrompt($documentText),
            default => $this->buildMateriPrompt($documentText),
        };

        $rawResponse = $this->callGeminiApi($prompt);

        return $this->parseAndValidateJson($rawResponse, $targetType);
    }

    /**
     * Kirim permintaan ke Gemini API endpoint generateContent dengan mekanisme fallback model otomatis.
     *
     * @throws RuntimeException
     */
    protected function callGeminiApi(string $prompt): string
    {
        // Daftar model yang akan dicoba berurutan jika model utama mengalami lonjakan beban (503) atau limit (429)
        $modelsToTry = array_unique([
            $this->model,
            'gemini-3.5-flash',
            'gemini-3.5-flash-lite',
            'gemini-3.8-flash',
        ]);

        $lastError = null;
        $lastStatus = 500;

        foreach ($modelsToTry as $candidateModel) {
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$candidateModel}:generateContent";

            try {
                /** @var PendingRequest $client */
                $client = Http::timeout($this->timeout)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'x-goog-api-key' => $this->apiKey,
                    ]);

                $response = $client->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'response_mime_type' => 'application/json',
                        'temperature' => 0.2,
                        'thinkingConfig' => [
                            'thinkingBudget' => 0,
                        ],
                    ],
                ]);

                if ($response->successful()) {
                    $responseData = $response->json();
                    $text = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? null;

                    if ($text !== null && trim($text) !== '') {
                        return trim($text);
                    }
                }

                $errorData = $response->json();
                $lastError = $errorData['error']['message'] ?? $response->body();
                $lastStatus = $response->status();

                // Jika error adalah 503 (overload) atau 429 (rate limit) atau 500, coba model berikutnya
                if (in_array($lastStatus, [503, 429, 500, 504], true)) {
                    continue;
                }

                // Jika error otentikasi (401/403), langsung lempar exception karena ganti model tidak akan mengubah izin
                break;
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
            }
        }

        // Format pesan error yang informatif dan ramah pengguna
        $friendlyMessage = match ($lastStatus) {
            503 => 'Server Google AI sedang mengalami lonjakan trafik antrean (High Demand). Silakan klik tombol "Coba Lagi" dalam beberapa detik.',
            429 => 'Batas kuota request Google AI per menit tercapai. Silakan tunggu 1 menit lalu coba lagi.',
            default => "Gagal terhubung ke AI (Status {$lastStatus}): {$lastError}",
        };

        throw new RuntimeException($friendlyMessage);
    }

    /**
     * Bangun prompt AI untuk mengekstrak modul Materi Pembelajaran Bahasa Inggris.
     */
    protected function buildMateriPrompt(string $documentText): string
    {
        return <<<PROMPT
Anda adalah asisten pengajar bahasa Inggris SMP/SMA profesional.
Tugas Anda adalah menganalisis teks dokumen materi berikut dan mengekstraknya ke dalam format JSON yang terstruktur rapi.

ATURAN EKSTRAKSI MATERI:
1. "judul": Judul materi pelajaran yang jelas dan representatif (contoh: "Simple Past Tense", "Descriptive Text").
2. "kategori": Pilih salah satu kategori bahasa Inggris yang paling relevan: "Grammar", "Vocabulary", "Reading", "Speaking", "Writing", "Tenses", "Expressions", atau "General".
3. "tingkat_kelas": Estimasi tingkatan kelas yang cocok, contoh: "Kelas 7", "Kelas 8", "Kelas 9", "Kelas 10", "Kelas 11", atau "Kelas 12".
4. "deskripsi_singkat": Rangkuman 1-2 kalimat tentang apa yang dipelajari dalam materi ini.
5. "penjelasan": Penjelasan materi lengkap, terstruktur, mudah dipahami siswa, menggunakan format teks/markdown rapi (definisi, rumus tata bahasa jika ada, penggunaan, dan aturan).
6. "contoh_teks": Contoh kalimat, percakapan (dialog), atau teks bacaan dalam bahasa Inggris beserta artinya dalam bahasa Indonesia.
7. "soals": Butir latihan soal pemahaman (jika ada di dokumen, ekstrak; jika tidak ada di dokumen, buatkan 3-5 butir soal pilihan ganda yang sangat relevan untuk menguji pemahaman materi ini).
   - "pertanyaan": Kalimat pertanyaan dalam bahasa Inggris atau instruksi soal.
   - "pilihan_a", "pilihan_b", "pilihan_c", "pilihan_d": Opsi jawaban.
   - "kunci_jawaban": Huruf kunci jawaban ('a', 'b', 'c', atau 'd').
   - "pembahasan": Penjelasan singkat mengapa pilihan tersebut benar.

FORMAT OUTPUT HARUS BERUPA JSON VALID SESUAI SKEMA BERIKUT:
{
  "judul": "string",
  "kategori": "string",
  "tingkat_kelas": "string",
  "deskripsi_singkat": "string",
  "penjelasan": "string",
  "contoh_teks": "string",
  "soals": [
    {
      "pertanyaan": "string",
      "pilihan_a": "string",
      "pilihan_b": "string",
      "pilihan_c": "string",
      "pilihan_d": "string",
      "kunci_jawaban": "a|b|c|d",
      "pembahasan": "string"
    }
  ]
}

DOKUMEN SUMBER:
{$documentText}
PROMPT;
    }

    /**
     * Bangun prompt AI untuk mengekstrak paket Kuis / Ujian Bahasa Inggris.
     */
    protected function buildQuizPrompt(string $documentText): string
    {
        return <<<PROMPT
Anda adalah asisten pembuatan kuis dan evaluasi bahasa Inggris SMP/SMA profesional.
Tugas Anda adalah menganalisis teks dokumen soal berikut dan mengekstraknya ke dalam format JSON paket kuis terstruktur.

ATURAN EKSTRAKSI KUIS:
1. "judul": Judul paket kuis/latihan (contoh: "Kuis Grammar: Present Continuous", "Ulangan Harian Reading Comprehension").
2. "kategori": Kategori kuis: "Grammar", "Vocabulary", "Reading", "Tenses", "Expressions", atau "General".
3. "tingkat_kelas": Estimasi kelas yang cocok ("Kelas 7", "Kelas 8", "Kelas 9", "Kelas 10", "Kelas 11", atau "Kelas 12").
4. "deskripsi": Petunjuk pengerjaan atau deskripsi kuis untuk siswa.
5. "durasi_menit": Estimasi durasi waktu pengerjaan kuis yang wajar (integer dalam menit, misalnya 15, 20, atau 30).
6. "soals": Daftar butir soal pilihan ganda yang diekstrak dari dokumen.
   - "nomor_soal": Nomor urut integer (1, 2, 3, dst).
   - "tipe_soal": "multiple_choice" atau "reading".
   - "reading_passage": Jika soal terkait bacaan teks/dialog, cantumkan teks bacaannya di sini. Jika soal mandiri, isi null.
   - "pertanyaan": Kalimat pertanyaan.
   - "pilihan_a", "pilihan_b", "pilihan_c", "pilihan_d": Opsi jawaban.
   - "kunci_jawaban": Huruf kunci jawaban ('a', 'b', 'c', atau 'd').
   - "pembahasan": Penjelasan singkat mengapa pilihan tersebut benar.

FORMAT OUTPUT HARUS BERUPA JSON VALID SESUAI SKEMA BERIKUT:
{
  "judul": "string",
  "kategori": "string",
  "tingkat_kelas": "string",
  "deskripsi": "string",
  "durasi_menit": 20,
  "soals": [
    {
      "nomor_soal": 1,
      "tipe_soal": "multiple_choice",
      "reading_passage": null,
      "pertanyaan": "string",
      "pilihan_a": "string",
      "pilihan_b": "string",
      "pilihan_c": "string",
      "pilihan_d": "string",
      "kunci_jawaban": "a|b|c|d",
      "pembahasan": "string"
    }
  ]
}

DOKUMEN SUMBER:
{$documentText}
PROMPT;
    }

    /**
     * Parsing string JSON, sanitasi, dan normalisasi struktur data.
     *
     * @return array<string, mixed>
     *
     * @throws RuntimeException
     */
    protected function parseAndValidateJson(string $rawJson, string $targetType): array
    {
        $cleanJson = $this->sanitizeJsonString($rawJson);

        try {
            /** @var array<string, mixed> $data */
            $data = json_decode($cleanJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('Gagal memproses format JSON dari Gemini AI: '.$e->getMessage().". Raw: {$cleanJson}", 0, $e);
        }

        if (! is_array($data)) {
            throw new RuntimeException('Hasil ekstraksi Gemini AI bukan berupa objek array.');
        }

        // Normalisasi field umum
        $data['judul'] = trim((string) ($data['judul'] ?? 'Materi Bahasa Inggris'));
        $data['kategori'] = trim((string) ($data['kategori'] ?? 'General'));
        $data['tingkat_kelas'] = trim((string) ($data['tingkat_kelas'] ?? 'Kelas 7'));

        if ($targetType === 'quiz') {
            $data['deskripsi'] = trim((string) ($data['deskripsi'] ?? 'Kerjakan latihan soal berikut dengan teliti.'));
            $data['durasi_menit'] = max(5, (int) ($data['durasi_menit'] ?? 20));
        } else {
            $data['deskripsi_singkat'] = trim((string) ($data['deskripsi_singkat'] ?? ''));
            $data['penjelasan'] = trim((string) ($data['penjelasan'] ?? ''));
            $data['contoh_teks'] = trim((string) ($data['contoh_teks'] ?? ''));
        }

        // Normalisasi butir soal
        $soals = [];
        if (isset($data['soals']) && is_array($data['soals'])) {
            $nomor = 1;
            foreach ($data['soals'] as $soal) {
                if (! is_array($soal)) {
                    continue;
                }

                $kunci = strtolower(trim((string) ($soal['kunci_jawaban'] ?? 'a')));
                if (! in_array($kunci, ['a', 'b', 'c', 'd'], true)) {
                    $kunci = 'a';
                }

                $soals[] = [
                    'nomor_soal' => (int) ($soal['nomor_soal'] ?? $nomor),
                    'tipe_soal' => (string) ($soal['tipe_soal'] ?? 'multiple_choice'),
                    'reading_passage' => isset($soal['reading_passage']) && trim((string) $soal['reading_passage']) !== '' ? trim((string) $soal['reading_passage']) : null,
                    'pertanyaan' => trim((string) ($soal['pertanyaan'] ?? '')),
                    'pilihan_a' => trim((string) ($soal['pilihan_a'] ?? '')),
                    'pilihan_b' => trim((string) ($soal['pilihan_b'] ?? '')),
                    'pilihan_c' => trim((string) ($soal['pilihan_c'] ?? '')),
                    'pilihan_d' => trim((string) ($soal['pilihan_d'] ?? '')),
                    'kunci_jawaban' => $kunci,
                    'pembahasan' => trim((string) ($soal['pembahasan'] ?? '')),
                ];
                $nomor++;
            }
        }
        $data['soals'] = $soals;

        return $data;
    }

    /**
     * Sanitasi string jika terdapat markdown codeblock seperti ```json ... ```.
     */
    protected function sanitizeJsonString(string $rawJson): string
    {
        $clean = trim($rawJson);

        // Hapus pembungkus markdown ```json ... ``` atau ``` ... ```
        if (preg_match('/^```(?:json)?\s*(.*?)\s*```$/s', $clean, $matches)) {
            $clean = trim($matches[1]);
        }

        return $clean;
    }
}
