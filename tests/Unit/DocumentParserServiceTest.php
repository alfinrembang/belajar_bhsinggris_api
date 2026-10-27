<?php

namespace Tests\Unit;

use App\Services\Ai\DocumentParserService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ZipArchive;

class DocumentParserServiceTest extends TestCase
{
    protected DocumentParserService $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new DocumentParserService;
    }

    /**
     * Parsing file txt menghasilkan teks bersih.
     */
    public function test_can_parse_plain_text(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_txt_');
        file_put_contents($tempFile, "Hello World!\nThis is a sample text.");

        try {
            $parsed = $this->parser->parse($tempFile, 'txt');
            $this->assertStringContainsString('Hello World!', $parsed);
            $this->assertStringContainsString('This is a sample text.', $parsed);
        } finally {
            @unlink($tempFile);
        }
    }

    /**
     * Format yang tidak didukung memicu InvalidArgumentException.
     */
    public function test_throws_exception_for_unsupported_format(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $tempFile = tempnam(sys_get_temp_dir(), 'test_unknown_');
        try {
            $this->parser->parse($tempFile, 'exe');
        } finally {
            @unlink($tempFile);
        }
    }

    /**
     * File yang tidak ada memicu InvalidArgumentException.
     */
    public function test_throws_exception_if_file_does_not_exist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->parser->parse('/path/to/nonexistent/file.docx');
    }

    /**
     * Ekstraksi teks dari struktur PowerPoint (.pptx).
     */
    public function test_can_parse_pptx_structure(): void
    {
        $tempPptx = tempnam(sys_get_temp_dir(), 'test_pptx_').'.pptx';

        $zip = new ZipArchive;
        $this->assertTrue($zip->open($tempPptx, ZipArchive::CREATE | ZipArchive::OVERWRITE));
        $zip->addFromString('ppt/slides/slide1.xml', '<p:sld><a:t>Materi Tenses Bab 1</a:t></p:sld>');
        $zip->addFromString('ppt/slides/slide2.xml', '<p:sld><a:t>Contoh Kalimat Present Simple</a:t></p:sld>');
        $zip->close();

        try {
            $result = $this->parser->parsePptx($tempPptx);
            $this->assertStringContainsString('Slide 1', $result);
            $this->assertStringContainsString('Materi Tenses Bab 1', $result);
            $this->assertStringContainsString('Slide 2', $result);
            $this->assertStringContainsString('Contoh Kalimat Present Simple', $result);
        } finally {
            @unlink($tempPptx);
        }
    }

    /**
     * Ekstraksi teks dari struktur Word (.docx).
     */
    public function test_can_parse_docx_structure(): void
    {
        $tempDocx = tempnam(sys_get_temp_dir(), 'test_docx_').'.docx';

        $zip = new ZipArchive;
        $this->assertTrue($zip->open($tempDocx, ZipArchive::CREATE | ZipArchive::OVERWRITE));
        $zip->addFromString('word/document.xml', '<w:document><w:p><w:t>Judul Modul Bahasa Inggris</w:t></w:p><w:p><w:t>Soal Pilihan Ganda 1</w:t></w:p></w:document>');
        $zip->close();

        try {
            $result = $this->parser->parseDocx($tempDocx);
            $this->assertStringContainsString('Judul Modul Bahasa Inggris', $result);
            $this->assertStringContainsString('Soal Pilihan Ganda 1', $result);
        } finally {
            @unlink($tempDocx);
        }
    }
}
