<?php

namespace Tests\Unit;

use App\Services\Ai\GeminiAiService;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

class GeminiAiServiceTest extends TestCase
{
    public function test_it_throws_exception_if_document_text_is_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Teks dokumen kosong.');

        $service = new GeminiAiService('fake-key');
        $service->extractContent('   ');
    }

    public function test_it_throws_exception_if_api_key_is_missing(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('GEMINI_API_KEY belum dikonfigurasi');

        $service = new GeminiAiService('');
        $service->extractContent('Some valid document text');
    }

    public function test_it_extracts_materi_successfully(): void
    {
        $mockJson = json_encode([
            'judul' => 'Simple Present Tense',
            'kategori' => 'Grammar',
            'tingkat_kelas' => 'Kelas 7',
            'deskripsi_singkat' => 'Materi tentang rutinitas sehari-hari.',
            'penjelasan' => 'Digunakan untuk fakta dan kebiasaan.',
            'contoh_teks' => 'I play football every Sunday.',
            'soals' => [
                [
                    'nomor_soal' => 1,
                    'pertanyaan' => 'He ... to school every day.',
                    'pilihan_a' => 'go',
                    'pilihan_b' => 'goes',
                    'pilihan_c' => 'went',
                    'pilihan_d' => 'gone',
                    'kunci_jawaban' => 'B',
                    'pembahasan' => 'Subjek He menggunakan goes.',
                ],
            ],
        ]);

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => $mockJson],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = new GeminiAiService('fake-api-key', 'gemini-3.5-flash');
        $result = $service->extractContent('Teks materi present tense', 'materi');

        $this->assertIsArray($result);
        $this->assertSame('Simple Present Tense', $result['judul']);
        $this->assertSame('Grammar', $result['kategori']);
        $this->assertSame('Kelas 7', $result['tingkat_kelas']);
        $this->assertCount(1, $result['soals']);
        $this->assertSame('b', $result['soals'][0]['kunci_jawaban']);
        $this->assertSame('He ... to school every day.', $result['soals'][0]['pertanyaan']);
    }

    public function test_it_extracts_quiz_successfully(): void
    {
        $mockJson = json_encode([
            'judul' => 'Kuis Vocabulary Hewan',
            'kategori' => 'Vocabulary',
            'tingkat_kelas' => 'Kelas 8',
            'deskripsi' => 'Kuis mengenal nama-nama hewan liar.',
            'durasi_menit' => 15,
            'soals' => [
                [
                    'nomor_soal' => 1,
                    'tipe_soal' => 'multiple_choice',
                    'pertanyaan' => 'Which animal has a trunk?',
                    'pilihan_a' => 'Lion',
                    'pilihan_b' => 'Elephant',
                    'pilihan_c' => 'Tiger',
                    'pilihan_d' => 'Bear',
                    'kunci_jawaban' => 'b',
                    'pembahasan' => 'Elephant has a long trunk.',
                ],
            ],
        ]);

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => $mockJson],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = new GeminiAiService('fake-api-key');
        $result = $service->extractContent('Teks kuis hewan', 'quiz');

        $this->assertIsArray($result);
        $this->assertSame('Kuis Vocabulary Hewan', $result['judul']);
        $this->assertSame(15, $result['durasi_menit']);
        $this->assertCount(1, $result['soals']);
        $this->assertSame('Which animal has a trunk?', $result['soals'][0]['pertanyaan']);
    }

    public function test_it_handles_markdown_wrapped_json_response(): void
    {
        $innerJson = json_encode([
            'judul' => 'Greeting & Leave Taking',
            'kategori' => 'Expressions',
            'tingkat_kelas' => 'Kelas 7',
            'deskripsi_singkat' => 'Ungkapan salam dalam bahasa Inggris.',
            'penjelasan' => 'Good morning, Good bye.',
            'contoh_teks' => 'Hello, nice to meet you.',
            'soals' => [],
        ]);

        $wrapped = "```json\n{$innerJson}\n```";

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => $wrapped],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = new GeminiAiService('fake-api-key');
        $result = $service->extractContent('Teks greeting', 'materi');

        $this->assertSame('Greeting & Leave Taking', $result['judul']);
        $this->assertSame('Expressions', $result['kategori']);
    }

    public function test_it_handles_gemini_api_error_response(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'error' => [
                    'code' => 403,
                    'message' => 'Quota exceeded',
                ],
            ], 403),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Status 403');

        $service = new GeminiAiService('fake-api-key');
        $service->extractContent('Some document', 'materi');
    }
}
