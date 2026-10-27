<?php

namespace Tests\Unit;

use App\Jobs\ProcessAiDocumentJob;
use App\Models\AiImport;
use App\Services\Ai\DocumentParserService;
use App\Services\Ai\GeminiAiService;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class ProcessAiDocumentJobTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_processes_document_successfully(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_doc_');
        file_put_contents($tempFile, 'Sample content of document');

        try {
            $aiImport = Mockery::mock(AiImport::class)->makePartial();
            $aiImport->id = 1;
            $aiImport->nama_file_asli = 'test.txt';
            $aiImport->file_path = $tempFile;
            $aiImport->format_file = 'txt';
            $aiImport->tipe_target = 'materi';
            $aiImport->status = 'pending';

            $aiImport->shouldReceive('refresh')->andReturnSelf();
            $aiImport->shouldReceive('isCompleted')->andReturn(false);
            $aiImport->shouldReceive('update')->andReturnUsing(function (array $attributes) use ($aiImport) {
                if (isset($attributes['status'])) {
                    $aiImport->status = $attributes['status'];
                }
                if (isset($attributes['hasil_ekstraksi_json'])) {
                    $aiImport->hasil_ekstraksi_json = $attributes['hasil_ekstraksi_json'];
                }
                if (array_key_exists('error_message', $attributes)) {
                    $aiImport->error_message = $attributes['error_message'];
                }

                return true;
            });

            $mockParser = Mockery::mock(DocumentParserService::class);
            $mockParser->shouldReceive('parse')
                ->once()
                ->with(Mockery::any(), 'txt')
                ->andReturn('Ekstraksi teks bersih materi bahasa Inggris.');

            $mockGemini = Mockery::mock(GeminiAiService::class);
            $mockGemini->shouldReceive('extractContent')
                ->once()
                ->with('Ekstraksi teks bersih materi bahasa Inggris.', 'materi')
                ->andReturn([
                    'judul' => 'Simple Past Tense',
                    'kategori' => 'Grammar',
                    'tingkat_kelas' => 'Kelas 8',
                    'deskripsi_singkat' => 'Materi past tense.',
                    'penjelasan' => 'Penjelasan past tense.',
                    'contoh_teks' => 'I ate an apple.',
                    'soals' => [],
                ]);

            $job = new ProcessAiDocumentJob($aiImport);
            $job->handle($mockParser, $mockGemini);

            $this->assertSame('completed', $aiImport->status);
            $this->assertNull($aiImport->error_message);
            $this->assertSame('Simple Past Tense', $aiImport->hasil_ekstraksi_json['judul']);
        } finally {
            @unlink($tempFile);
        }
    }

    public function test_it_skips_processing_if_already_completed(): void
    {
        $aiImport = Mockery::mock(AiImport::class)->makePartial();
        $aiImport->id = 2;
        $aiImport->status = 'completed';

        $aiImport->shouldReceive('refresh')->andReturnSelf();
        $aiImport->shouldReceive('isCompleted')->andReturn(true);

        $mockParser = Mockery::mock(DocumentParserService::class);
        $mockParser->shouldNotReceive('parse');

        $mockGemini = Mockery::mock(GeminiAiService::class);
        $mockGemini->shouldNotReceive('extractContent');

        $job = new ProcessAiDocumentJob($aiImport);
        $job->handle($mockParser, $mockGemini);

        $this->assertSame('completed', $aiImport->status);
    }

    public function test_it_marks_import_as_failed_when_file_not_found(): void
    {
        $aiImport = Mockery::mock(AiImport::class)->makePartial();
        $aiImport->id = 3;
        $aiImport->nama_file_asli = 'missing.docx';
        $aiImport->file_path = 'dokumen_ai/file_tidak_pernah_ada.docx';
        $aiImport->format_file = 'docx';
        $aiImport->status = 'pending';

        $aiImport->shouldReceive('refresh')->andReturnSelf();
        $aiImport->shouldReceive('isCompleted')->andReturn(false);
        $aiImport->shouldReceive('update')->andReturnUsing(function (array $attributes) use ($aiImport) {
            if (isset($attributes['status'])) {
                $aiImport->status = $attributes['status'];
            }
            if (isset($attributes['error_message'])) {
                $aiImport->error_message = $attributes['error_message'];
            }

            return true;
        });

        $mockParser = Mockery::mock(DocumentParserService::class);
        $mockGemini = Mockery::mock(GeminiAiService::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File fisik dokumen tidak ditemukan');

        try {
            $job = new ProcessAiDocumentJob($aiImport);
            $job->handle($mockParser, $mockGemini);
        } finally {
            $this->assertSame('failed', $aiImport->status);
            $this->assertStringContainsString('File fisik dokumen tidak ditemukan', $aiImport->error_message);
        }
    }

    public function test_failed_method_updates_status_properly(): void
    {
        $aiImport = Mockery::mock(AiImport::class)->makePartial();
        $aiImport->id = 4;
        $aiImport->status = 'processing';

        $aiImport->shouldReceive('refresh')->andReturnSelf();
        $aiImport->shouldReceive('update')->with(Mockery::on(function (array $attributes) use ($aiImport) {
            $aiImport->status = $attributes['status'];
            $aiImport->error_message = $attributes['error_message'];

            return true;
        }))->andReturn(true);

        $job = new ProcessAiDocumentJob($aiImport);
        $job->failed(new RuntimeException('Koneksi timeout ke server AI.'));

        $this->assertSame('failed', $aiImport->status);
        $this->assertSame('Koneksi timeout ke server AI.', $aiImport->error_message);
    }
}
