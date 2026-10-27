<?php

namespace App\Services\Ai;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use Smalot\PdfParser\Parser as PdfParser;
use ZipArchive;

class DocumentParserService
{
    /**
     * Parse dokumen (PPTX, DOCX, PDF, TXT) menjadi teks bersih.
     *
     * @throws Exception
     */
    public function parse(string $filePath, ?string $format = null): string
    {
        if (! file_exists($filePath)) {
            throw new InvalidArgumentException("File dokumen tidak ditemukan pada path: {$filePath}");
        }

        $extension = strtolower($format ?: pathinfo($filePath, PATHINFO_EXTENSION));

        $text = match ($extension) {
            'pptx' => $this->parsePptx($filePath),
            'docx' => $this->parseDocx($filePath),
            'pdf' => $this->parsePdf($filePath),
            'txt' => (string) file_get_contents($filePath),
            default => throw new InvalidArgumentException("Format dokumen .{$extension} belum didukung. Gunakan .pptx, .docx, atau .pdf."),
        };

        return $this->cleanText($text);
    }

    /**
     * Ekstraksi teks dari presentasi PowerPoint (.pptx) menggunakan ZipArchive bawaan PHP.
     */
    public function parsePptx(string $filePath): string
    {
        $zip = new ZipArchive;
        if ($zip->open($filePath) !== true) {
            throw new RuntimeException('Gagal membuka file PowerPoint (.pptx). Pastikan file tidak terkunci atau rusak.');
        }

        $slides = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // Cari file XML slide, contoh: ppt/slides/slide1.xml
            if (preg_match('#^ppt/slides/slide(\d+)\.xml$#i', $filename, $matches)) {
                $slideIndex = (int) $matches[1];
                $xmlContent = $zip->getFromIndex($i);

                if ($xmlContent !== false) {
                    $slides[$slideIndex] = $this->extractTextFromXml($xmlContent);
                }
            }
        }

        $zip->close();

        if (empty($slides)) {
            return '';
        }

        // Urutkan slide berdasarkan nomor halaman
        ksort($slides);

        $output = [];
        foreach ($slides as $num => $content) {
            $cleanSlide = trim($content);
            if ($cleanSlide !== '') {
                $output[] = "=== Slide {$num} ===\n{$cleanSlide}";
            }
        }

        return implode("\n\n", $output);
    }

    /**
     * Ekstraksi teks dari dokumen Word (.docx) menggunakan ZipArchive bawaan PHP.
     */
    public function parseDocx(string $filePath): string
    {
        $zip = new ZipArchive;
        if ($zip->open($filePath) !== true) {
            throw new RuntimeException('Gagal membuka file Word (.docx). Pastikan file tidak terkunci atau rusak.');
        }

        $xmlContent = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xmlContent === false) {
            throw new RuntimeException('Format isi file Word (.docx) tidak valid atau kosong.');
        }

        // Ganti penutup paragraf </w:p> menjadi newline agar format paragraf terjaga
        $xmlContent = str_replace(['</w:p>', '</w:tr>'], ["\n", "\n"], $xmlContent);

        return $this->extractTextFromXml($xmlContent);
    }

    /**
     * Ekstraksi teks dari dokumen PDF (.pdf) menggunakan Smalot PdfParser.
     */
    public function parsePdf(string $filePath): string
    {
        try {
            $parser = new PdfParser;
            $pdf = $parser->parseFile($filePath);

            return $pdf->getText();
        } catch (Exception $e) {
            throw new RuntimeException('Gagal mengekstrak teks dari file PDF: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * Ekstraksi teks bersih dari string XML dokumen.
     */
    protected function extractTextFromXml(string $xmlContent): string
    {
        // Strip tag XML dan decode entitas HTML/XML
        $text = strip_tags($xmlContent);

        return html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    /**
     * Sanitasi dan pembersihan teks hasil ekstraksi.
     */
    protected function cleanText(string $text): string
    {
        // Normalisasi line break
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Hapus karakter null byte dan non-printable
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text) ?? $text;

        // Bersihkan whitespace berlebih per baris
        $lines = explode("\n", $text);
        $cleanedLines = array_map(fn ($line) => trim(preg_replace('/[ \t]+/u', ' ', $line) ?? ''), $lines);

        // Maksimal 2 baris kosong berturut-turut
        $result = implode("\n", $cleanedLines);
        $result = preg_replace("/\n{3,}/u", "\n\n", $result) ?? $result;

        return trim($result);
    }
}
