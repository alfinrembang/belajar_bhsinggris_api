<?php

namespace App\Services;

use App\Models\FishingSoal;
use App\Models\PronounceWord;
use App\Models\SentenceSoal;
use App\Models\WerewolfSoal;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use SimpleXMLElement;
use Throwable;
use ZipArchive;

class GameExcelService
{
    /**
     * Membaca isi file Excel (.xlsx, .xls) atau file CSV (.csv) menjadi array baris data.
     */
    public static function parseRows(string $filePath, string $extension): array
    {
        $extension = strtolower($extension);

        if ($extension === 'csv' || $extension === 'txt') {
            return self::parseCsv($filePath);
        }

        if (in_array($extension, ['xlsx', 'xls'])) {
            try {
                // Gunakan PhpSpreadsheet IOFactory untuk pembacaan akurat dan komprehensif
                $spreadsheet = IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray('', true, true, false);

                // Bersihkan baris kosong
                $cleanRows = [];
                foreach ($rows as $row) {
                    $hasContent = false;
                    $rowClean = [];
                    foreach ($row as $cell) {
                        $cellStr = trim((string) $cell);
                        if ($cellStr !== '') {
                            $hasContent = true;
                        }
                        $rowClean[] = $cellStr;
                    }
                    if ($hasContent) {
                        $cleanRows[] = $rowClean;
                    }
                }

                if (! empty($cleanRows)) {
                    return $cleanRows;
                }
            } catch (Throwable $e) {
                // Jika IOFactory gagal, coba fallback parseXlsx bawaan
            }

            return self::parseXlsx($filePath);
        }

        return [];
    }

    /**
     * Parse file CSV dengan deteksi otomatis delimiter (koma, titik koma, atau tab).
     */
    protected static function parseCsv(string $filePath): array
    {
        $rows = [];
        if (! file_exists($filePath) || ! is_readable($filePath)) {
            return $rows;
        }

        $content = file_get_contents($filePath);
        // Hapus BOM UTF-8 jika ada
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

        // Deteksi delimiter: cek baris pertama
        $firstLine = strtok($content, "\r\n");
        $delimiter = ',';
        if ($firstLine !== false) {
            $semicolons = substr_count($firstLine, ';');
            $commas = substr_count($firstLine, ',');
            $tabs = substr_count($firstLine, "\t");
            if ($semicolons > $commas && $semicolons > $tabs) {
                $delimiter = ';';
            } elseif ($tabs > $commas && $tabs > $semicolons) {
                $delimiter = "\t";
            }
        }

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        while (($data = fgetcsv($stream, 0, $delimiter)) !== false) {
            $cleanRow = array_map(function ($val) {
                return trim((string) $val);
            }, $data);

            if (count(array_filter($cleanRow)) === 0) {
                continue;
            }

            $rows[] = $cleanRow;
        }

        fclose($stream);

        return $rows;
    }

    /**
     * Parse file Excel .xlsx murni menggunakan ZipArchive & SimpleXML bawaan PHP (fallback).
     */
    protected static function parseXlsx(string $filePath): array
    {
        $rows = [];
        $zip = new ZipArchive;

        if ($zip->open($filePath) !== true) {
            return $rows;
        }

        $sharedStrings = [];
        $stringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($stringsXml !== false) {
            $xml = new SimpleXMLElement($stringsXml);
            foreach ($xml->si as $si) {
                if (isset($si->t)) {
                    $sharedStrings[] = (string) $si->t;
                } elseif (isset($si->r)) {
                    $textParts = [];
                    foreach ($si->r as $r) {
                        $textParts[] = (string) $r->t;
                    }
                    $sharedStrings[] = implode('', $textParts);
                } else {
                    $sharedStrings[] = '';
                }
            }
        }

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXml === false) {
            $zip->close();

            return $rows;
        }

        $xml = new SimpleXMLElement($sheetXml);
        if (! isset($xml->sheetData->row)) {
            $zip->close();

            return $rows;
        }

        foreach ($xml->sheetData->row as $rowNode) {
            $rowCells = [];
            $currentColIdx = 0;

            foreach ($rowNode->c as $cell) {
                $cellRef = (string) $cell['r'];
                preg_match('/^([A-Z]+)(\d+)$/', $cellRef, $matches);
                $colLetters = $matches[1] ?? 'A';
                $targetColIdx = self::lettersToColIndex($colLetters);

                while ($currentColIdx < $targetColIdx) {
                    $rowCells[] = '';
                    $currentColIdx++;
                }

                $cellType = (string) $cell['t'];
                $val = isset($cell->v) ? (string) $cell->v : '';

                if ($cellType === 's') {
                    $idx = (int) $val;
                    $cellValue = $sharedStrings[$idx] ?? '';
                } elseif ($cellType === 'inlineStr' && isset($cell->is->t)) {
                    $cellValue = (string) $cell->is->t;
                } else {
                    $cellValue = $val;
                }

                $rowCells[] = trim($cellValue);
                $currentColIdx++;
            }

            if (count(array_filter($rowCells)) > 0) {
                $rows[] = $rowCells;
            }
        }

        $zip->close();

        return $rows;
    }

    /**
     * Mengonversi nama kolom huruf Excel (A, B, C, ... AA) ke indeks 0-based.
     */
    protected static function lettersToColIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $num = 0;
        for ($i = 0; $i < strlen($letters); $i++) {
            $num = $num * 26 + (ord($letters[$i]) - ord('A') + 1);
        }

        return max(0, $num - 1);
    }

    /**
     * Membangun pemetaan index kolom dari baris header.
     */
    protected static function buildHeaderMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $idx => $val) {
            $clean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string) $val));
            if ($clean !== '') {
                $map[$clean] = $idx;
            }
        }

        return $map;
    }

    /**
     * Menemukan baris index header secara fleksibel (mendukung file dengan title banner di baris 1-3).
     */
    protected static function findHeaderRowIndex(array $rawRows, array $indicatorKeywords): ?int
    {
        $maxScan = min(10, count($rawRows));
        for ($i = 0; $i < $maxScan; $i++) {
            $row = $rawRows[$i];
            $filledCols = count(array_filter($row, function ($c) {
                return trim((string) $c) !== '';
            }));
            if ($filledCols < 3) {
                continue; // Lewati judul dan panduan atas yang hanya mengisi sel A1 atau sedikit sel
            }

            $rowStr = strtolower(implode(' ', $row));
            foreach ($indicatorKeywords as $kw) {
                if (str_contains($rowStr, strtolower($kw))) {
                    return $i;
                }
            }
        }

        return ! empty($rawRows) ? 0 : null;
    }

    /**
     * Helper mengambil nilai dari baris array baik melalui nama header maupun urutan indeks kolom.
     */
    protected static function getVal(array $row, array $headerMap, array $keys, int $fallbackIdx, string $default = ''): string
    {
        // 1. Cek kecocokan persis pada header
        foreach ($keys as $k) {
            $cleanKey = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $k));
            if (isset($headerMap[$cleanKey]) && isset($row[$headerMap[$cleanKey]])) {
                $val = trim((string) $row[$headerMap[$cleanKey]]);
                if ($val !== '') {
                    return $val;
                }
            }
        }

        // 2. Cek kecocokan substring pada header
        foreach ($keys as $k) {
            $cleanKey = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $k));
            foreach ($headerMap as $hKey => $idx) {
                if (str_contains($hKey, $cleanKey) && isset($row[$idx])) {
                    $val = trim((string) $row[$idx]);
                    if ($val !== '') {
                        return $val;
                    }
                }
            }
        }

        // 3. Fallback ke indeks kolom standar
        if (isset($row[$fallbackIdx])) {
            $val = trim((string) $row[$fallbackIdx]);
            if ($val !== '') {
                return $val;
            }
        }

        return $default;
    }

    // ==============================================================
    // 1. GENERATOR TEMPLATE EXCEL (.XLSX) RESMI BER-STYLE RAPI
    // ==============================================================

    /**
     * Helper terpusat untuk merender template Excel bergaya profesional sesuai gambar referensi (Gambar 2).
     */
    protected static function applyTemplateStyling(
        Spreadsheet $spreadsheet,
        string $sheetTitle,
        string $titleBanner,
        string $subtitleBanner,
        array $headers,
        array $sampleData,
        array $centerColumns = ['A']
    ): Spreadsheet {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($sheetTitle, 0, 31));

        $colCount = count($headers);
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);

        // Baris 1: Judul Utama Merged
        $sheet->setCellValue('A1', $titleBanner);
        $sheet->mergeCells("A1:{$lastColLetter}1");
        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'name' => 'Segoe UI',
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => '1E293B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Baris 2: Subtitle / Catatan Panduan Merged
        $sheet->setCellValue('A2', $subtitleBanner);
        $sheet->mergeCells("A2:{$lastColLetter}2");
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'name' => 'Segoe UI',
                'italic' => true,
                'size' => 9.5,
                'color' => ['rgb' => '64748B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Baris 3: Pemisah kosong
        $sheet->getRowDimension(3)->setRowHeight(8);

        // Baris 4: Header Kolom Berlatar Hijau (#4EA87D) & Teks Putih Tebal
        $headerRowIdx = 4;
        $sheet->getRowDimension($headerRowIdx)->setRowHeight(28);

        $colIdx = 1;
        foreach ($headers as $headerText) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx);
            $sheet->setCellValue("{$colLetter}{$headerRowIdx}", $headerText);
            $colIdx++;
        }

        $headerStyle = [
            'font' => [
                'name' => 'Segoe UI',
                'bold' => true,
                'size' => 10.5,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4EA87D'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => false,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '3B8361'],
                ],
            ],
        ];
        $sheet->getStyle("A{$headerRowIdx}:{$lastColLetter}{$headerRowIdx}")->applyFromArray($headerStyle);

        // Baris 5+: Data Contoh
        $dataStartRow = 5;
        $currentRow = $dataStartRow;

        foreach ($sampleData as $row) {
            $sheet->getRowDimension($currentRow)->setRowHeight(23);
            $cIdx = 1;
            foreach ($row as $val) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx);
                $sheet->setCellValueExplicit("{$colLetter}{$currentRow}", (string) $val, DataType::TYPE_STRING);
                $cIdx++;
            }
            $currentRow++;
        }

        $lastDataRow = max($dataStartRow, $currentRow - 1);

        // Styling Border & Font untuk Seluruh Data
        $dataBorderStyle = [
            'font' => [
                'name' => 'Segoe UI',
                'size' => 10,
                'color' => ['rgb' => '1E293B'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle("A{$dataStartRow}:{$lastColLetter}{$lastDataRow}")->applyFromArray($dataBorderStyle);

        // Format kolom rata tengah
        foreach ($centerColumns as $cCol) {
            $sheet->getStyle("{$cCol}{$dataStartRow}:{$cCol}{$lastDataRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Auto-Size Kolom agar tidak berhimpitan seperti Gambar 1
        for ($i = 1; $i <= $colCount; $i++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    /**
     * Menghasilkan Template Excel (.xlsx) untuk Game Tangkap Ikan (Word Fishing).
     */
    public static function generateFishingTemplate(): Spreadsheet
    {
        $headers = [
            'NO',
            'LEVEL (1-9)',
            'TINGKAT KESULITAN (mudah/sedang/sulit)',
            'KOSAKATA (ENGLISH WORD)',
            'JENIS KATA (PART OF SPEECH)',
            'ARTI / JAWABAN BENAR',
            'PILIHAN PENGECOH 1',
            'PILIHAN PENGECOH 2',
            'PILIHAN PENGECOH 3',
            'PENJELASAN EDUKASI',
            'CONTOH KALIMAT',
        ];

        $sampleData = [
            [
                '1',
                '1',
                'mudah',
                'DOLPHIN',
                'Noun (Kata Benda)',
                'Lumba-Lumba',
                'Hiu Putih',
                'Ikan Paus',
                'Penyu Laut',
                'Dolphin adalah mamalia laut yang sangat cerdas, bersahabat, dan bernapas dengan paru-paru.',
                'The dolphin jumped gracefully through the ocean waves.',
            ],
            [
                '2',
                '2',
                'sedang',
                'GENEROUS',
                'Adjective (Kata Sifat)',
                'Dermawan / Suka Berbagi',
                'Pelit',
                'Keras Kepala',
                'Pemarah',
                'Generous menggambarkan sifat seseorang yang senang memberi atau berbagi rezeki kepada sesama.',
                'He was generous enough to share his lunch with his classmates.',
            ],
            [
                '3',
                '3',
                'sulit',
                'PRESERVATION',
                'Noun (Kata Benda)',
                'Pelestarian / Konservasi',
                'Perusakan Hutan',
                'Pengabaian',
                'Pencemaran Udara',
                'Preservation berarti tindakan merawat dan melindungi alam/budaya agar tetap utuh dan lestari.',
                'Forest preservation is essential for protecting endangered wildlife.',
            ],
            [
                '4',
                '1',
                'mudah',
                'BUTTERFLY',
                'Noun (Kata Benda)',
                'Kupu-Kupu',
                'Capung',
                'Lebah Madu',
                'Belalang',
                'Butterfly adalah serangga bersayap indah yang mengalami metamorfosis sempurna dari ulat.',
                'A colorful butterfly landed gently on the blooming flower.',
            ],
        ];

        $spreadsheet = new Spreadsheet;

        return self::applyTemplateStyling(
            $spreadsheet,
            'Soal Tangkap Ikan',
            'TEMPLATE IMPORT SOAL GAME TANGKAP IKAN (WORD FISHING)',
            'Panduan Guru: Isikan kosakata dan pilihan jawaban. Sistem otomatis melewati kata yang sudah ada di database (anti-duplikat aktif).',
            $headers,
            $sampleData,
            ['A', 'B', 'C', 'E']
        );
    }

    /**
     * Menghasilkan Template Excel (.xlsx) untuk Game Tebak Serigala (Wolf vs Sheep).
     */
    public static function generateWerewolfTemplate(): Spreadsheet
    {
        $headers = [
            'NO',
            'KATEGORI / TEMA',
            'KATA DOMBA (KATA A)',
            'ARTI KATA DOMBA',
            'PETUNJUK DOMBA (PISAHKAN TITIK KOMA ;)',
            'KATA SERIGALA (KATA B)',
            'ARTI KATA SERIGALA',
            'PETUNJUK SERIGALA (PISAHKAN TITIK KOMA ;)',
            'PENJELASAN PERBEDAAN BAHASA INGGRIS',
        ];

        $sampleData = [
            [
                '1',
                'Nature & Landscape',
                'FOREST',
                'Hutan Beriklim Sedang',
                'Has tall pine and oak trees; Cooler shaded canopy; Home to deer and brown bears; Open ground covered in leaves',
                'JUNGLE',
                'Rimba Hutan Hujan Tropis',
                'Dense tangled tropical vegetation; High humidity and heavy rainfall; Inhabited by tigers and monkeys; Thick vines everywhere',
                'Forest refers to temperate woodlands with open floors, while Jungle is a dense, impenetrable tropical rainforest.',
            ],
            [
                '2',
                'Animals',
                'WOLF',
                'Serigala Liar',
                'Wild canine hunting in organized packs; Howls loudly under the full moon; Thick grey fur and sharp instincts; Untamed wilderness predator',
                'DOG',
                'Anjing Peliharaan',
                'Loyal best friend of humans; Barks happily and wags tail; Lives indoors as beloved family pet; Enjoys playing fetch with balls',
                'Wolf is a fierce wild predator living in packs, whereas Dog is a domesticated animal companion living closely with humans.',
            ],
            [
                '3',
                'Daily Drinks',
                'COFFEE',
                'Kopi',
                'Brewed from dark roasted coffee beans; High caffeine to boost morning energy; Rich roasted aroma and bitter taste; Often mixed with milk or latte art',
                'TEA',
                'Teh',
                'Steeped from dried camellia leaves; Lighter aroma and calming sensation; Often served with lemon or organic honey; Traditional British afternoon ritual',
                'Coffee is made from brewed roasted beans with strong caffeine, whereas Tea is an infusion of dried tea leaves with herbal undertones.',
            ],
        ];

        $spreadsheet = new Spreadsheet;

        return self::applyTemplateStyling(
            $spreadsheet,
            'Kata Tebak Serigala',
            'TEMPLATE IMPORT PASANGAN KATA TEBAK SERIGALA (WOLF VS SHEEP)',
            'Panduan Guru: Pasangan kata kartu yang sudah ada akan otomatis dilewati agar database tidak duplikat. Pisahkan petunjuk dengan tanda titik koma (;).',
            $headers,
            $sampleData,
            ['A', 'B']
        );
    }

    /**
     * Menghasilkan Template Excel (.xlsx) untuk Game Adu Lafal (Pronounce Hero).
     */
    public static function generatePronounceTemplate(): Spreadsheet
    {
        $headers = [
            'NO',
            'KOSAKATA (ENGLISH WORD)',
            'FONETIK IPA',
            'JENIS KATA (PART OF SPEECH)',
            'ARTI KATA (BAHASA INDONESIA)',
            'TINGKAT KESULITAN (mudah/sedang/sulit)',
            'TIPS PELAFALAN (OPSIONAL)',
            'CONTOH KALIMAT (ENGLISH EXAMPLE)',
        ];

        $sampleData = [
            [
                '1',
                'Wednesday',
                '/ˈwɛnzdeɪ/',
                'Noun (Kata Benda)',
                'Hari Rabu',
                'mudah',
                "Huruf 'd' pertama dan 'e' kedua bersifat silent (tidak dibaca). Lafalkan langsung: WENZ-day.",
                'We have an exciting English quiz every Wednesday.',
            ],
            [
                '2',
                'Comfortable',
                '/ˈkʌmftərbəl/',
                'Adjective (Kata Sifat)',
                'Nyaman',
                'sedang',
                'Sering salah dilafalkan 4 suku kata. Penutur asli melafalkannya 3 suku kata ringkas: COMF-ter-ble.',
                'This modern sofa is very comfortable to sit on.',
            ],
            [
                '3',
                'Entrepreneur',
                '/ˌɒntrəprəˈnɜːr/',
                'Noun (Kata Benda)',
                'Pengusaha / Wirausahawan',
                'sulit',
                'Kata serapan dari bahasa Prancis. Tekanan kata utama berada pada suku kata terakhir: on-truh-pruh-NUR.',
                'She is a successful and creative technology entrepreneur.',
            ],
            [
                '4',
                'Recipe',
                '/ˈrɛsɪpi/',
                'Noun (Kata Benda)',
                'Resep Masakan',
                'sedang',
                "Sering keliru dilafalkan 're-saip'. Pengucapan yang tepat memiliki 3 suku kata: REH-suh-pee.",
                'Follow this traditional recipe to bake delicious chocolate chip cookies.',
            ],
        ];

        $spreadsheet = new Spreadsheet;

        return self::applyTemplateStyling(
            $spreadsheet,
            'Kosakata Adu Lafal',
            'TEMPLATE IMPORT KOSAKATA PELAFALAN (ADU LAFAL / PRONOUNCE HERO)',
            'Panduan Guru: Format rapi otomatis. Kosakata yang sudah ada di database akan dilewati secara otomatis sehingga bebas dari data dobel/redundansi.',
            $headers,
            $sampleData,
            ['A', 'D', 'F']
        );
    }

    /**
     * Menghasilkan Template Excel (.xlsx) untuk Game Susun Kalimat (Sentence Builder) - Mode Solo.
     */
    public static function generateSentenceTemplate(): Spreadsheet
    {
        $headers = [
            'NO',
            'LEVEL (1-9)',
            'TINGKAT KESULITAN (mudah/sedang/sulit)',
            'KALIMAT BAHASA INGGRIS',
            'ARTI BAHASA INDONESIA',
            'KATEGORI TENSES',
            'TIPS GRAMMAR (OPSIONAL)',
        ];

        $sampleData = [
            [
                '1',
                '1',
                'mudah',
                'I drink fresh milk every morning.',
                'Saya minum susu segar setiap pagi.',
                'Simple Present Tense',
                'Gunakan Verb 1 (drink) untuk kebiasaan sehari-hari.',
            ],
            [
                '2',
                '2',
                'sedang',
                'She bought a new laptop yesterday.',
                'Dia membeli laptop baru kemarin.',
                'Simple Past Tense',
                'Gunakan Verb 2 (bought) untuk kejadian masa lampau.',
            ],
            [
                '3',
                '2',
                'sedang',
                'They are studying English in the classroom.',
                'Mereka sedang belajar bahasa Inggris di dalam kelas.',
                'Present Continuous',
                'Pola to be (are) + Verb-ing untuk aktivitas yang sedang berlangsung saat ini.',
            ],
            [
                '4',
                '3',
                'sulit',
                'The delicious chocolate cake was baked by my mother.',
                'Kue cokelat yang lezat itu dipanggang oleh ibuku.',
                'Passive Voice',
                'Pola pasif lampau menggunakan was/were + Verb 3 (baked).',
            ],
            [
                '5',
                '3',
                'sulit',
                'If you study diligently, you will pass the exam successfully.',
                'Jika kamu belajar dengan tekun, kamu akan lulus ujian dengan sukses.',
                'Conditional Sentence Type 1',
                'Klausa if menggunakan Simple Present, dan klausa hasil menggunakan will + Verb 1.',
            ],
        ];

        $spreadsheet = new Spreadsheet;

        return self::applyTemplateStyling(
            $spreadsheet,
            'Susun Kalimat',
            'TEMPLATE IMPORT SOAL SUSUN KALIMAT (SENTENCE BUILDER - SOLO)',
            'Panduan Guru: Cukup tuliskan kalimat bahasa Inggris utuh. Sistem otomatis memecah kata dan mengacak urutannya saat dimainkan siswa. Kalimat duplikat otomatis dilewati.',
            $headers,
            $sampleData,
            ['A', 'B', 'C', 'F']
        );
    }

    // ==============================================================
    // 2. IMPORT SOAL & KOSAKATA DENGAN PERLINDUNGAN ANTI-DUPLIKAT KETAT
    // ==============================================================

    /**
     * Import Butir Soal Game Tangkap Ikan (Word Fishing) dengan Logika Anti-Duplikat (Upsert/Skip).
     */
    public static function importFishing(string $filePath, string $extension): array
    {
        $rawRows = self::parseRows($filePath, $extension);
        if (empty($rawRows)) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['File kosong atau format tidak dapat dibaca.']];
        }

        // Cari baris header secara fleksibel (mendukung template dengan title banner)
        $headerRowIdx = self::findHeaderRowIndex($rawRows, ['word', 'kosakata', 'level', 'difficulty', 'jawaban', 'arti']);
        if ($headerRowIdx === null || ! isset($rawRows[$headerRowIdx])) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['Baris judul kolom (header) tidak ditemukan dalam file.']];
        }

        $header = $rawRows[$headerRowIdx];
        $headerMap = self::buildHeaderMap($header);

        // Ambil baris data setelah header
        $dataRows = array_slice($rawRows, $headerRowIdx + 1);
        if (empty($dataRows)) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['File hanya berisi baris judul header tanpa ada data soal.']];
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $seenWordsInFile = [];

        foreach ($dataRows as $lineIdx => $row) {
            $rowNum = $headerRowIdx + 2 + $lineIdx;

            // Lewati baris kosong
            if (count(array_filter($row)) === 0) {
                continue;
            }

            // Ambil nilai kolom berdasarkan header atau urutan indeks standar
            $levelVal = self::getVal($row, $headerMap, ['level', 'tingkat'], 1, '1');
            $level = (int) preg_replace('/[^0-9]/', '', $levelVal);
            if ($level < 1 || $level > 9) {
                $level = 1;
            }

            $difficulty = strtolower(self::getVal($row, $headerMap, ['difficulty', 'tingkatkesulitan', 'kesulitan'], 2, 'mudah'));
            $word = strtoupper(trim(self::getVal($row, $headerMap, ['word', 'kosakataenglishword', 'kosakata', 'katabahasainggris'], 3, '')));
            $pos = self::getVal($row, $headerMap, ['partofspeech', 'jeniskatapartofspeech', 'jeniskata', 'pos'], 4, 'Noun (Kata Benda)');
            $meaning = trim(self::getVal($row, $headerMap, ['meaning', 'artikuncijawabanbenar', 'artijawabanbenar', 'arti', 'terjemahan'], 5, ''));
            $opt1 = trim(self::getVal($row, $headerMap, ['pilihanpengecoh1', 'opsipengecoh1', 'pengecoh1', 'option2'], 6, ''));
            $opt2 = trim(self::getVal($row, $headerMap, ['pilihanpengecoh2', 'opsipengecoh2', 'pengecoh2', 'option3'], 7, ''));
            $opt3 = trim(self::getVal($row, $headerMap, ['pilihanpengecoh3', 'opsipengecoh3', 'pengecoh3', 'option4'], 8, ''));
            $explanation = trim(self::getVal($row, $headerMap, ['penjelasanedukasi', 'penjelasan', 'explanation'], 9, ''));
            $example = trim(self::getVal($row, $headerMap, ['contohkalimat', 'examplesentence', 'contoh'], 10, ''));

            if (empty($word) || empty($meaning)) {
                $errors[] = "Baris #{$rowNum}: Kosakata dan Arti tidak boleh kosong.";

                continue;
            }

            // Normalisasi Part of Speech
            if (! str_contains($pos, '(')) {
                $pos = match (strtolower(trim($pos))) {
                    'noun' => 'Noun (Kata Benda)',
                    'verb' => 'Verb (Kata Kerja)',
                    'adjective' => 'Adjective (Kata Sifat)',
                    'adverb' => 'Adverb (Kata Keterangan)',
                    default => $pos,
                };
            }

            // Normalisasi Pilihan Jawaban (Kunci Jawaban + Pengecoh)
            $options = [$meaning];
            if (! empty($opt1)) {
                $options[] = $opt1;
            }
            if (! empty($opt2)) {
                $options[] = $opt2;
            }
            if (! empty($opt3)) {
                $options[] = $opt3;
            }

            // Fallback jika guru belum mengisi semua pilihan
            while (count($options) < 4) {
                $options[] = 'Pengecoh '.count($options);
            }

            // PERLINDUNGAN ANTI-DUPLIKAT (Cek file lokal & cek database)
            if (isset($seenWordsInFile[$word])) {
                $skipped++;

                continue; // Duplikat dalam file yang sama
            }
            $seenWordsInFile[$word] = true;

            $existsInDb = FishingSoal::whereRaw('UPPER(TRIM(word)) = ?', [$word])->exists();
            if ($existsInDb) {
                $skipped++;

                continue; // Sudah ada di database, lewati demi performa dan efisiensi
            }

            FishingSoal::create([
                'level' => max(1, min(9, $level)),
                'difficulty' => in_array($difficulty, ['mudah', 'sedang', 'sulit']) ? $difficulty : 'mudah',
                'word' => $word,
                'part_of_speech' => $pos,
                'meaning' => $meaning,
                'options' => array_slice($options, 0, 4),
                'explanation' => $explanation ?: null,
                'example_sentence' => $example ?: null,
                'pull_energy' => 20,
            ]);

            $imported++;
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'total' => $imported + $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * Import Pasangan Kata Tebak Serigala (Wolf vs Sheep) dengan Logika Anti-Duplikat.
     */
    public static function importWerewolf(string $filePath, string $extension): array
    {
        $rawRows = self::parseRows($filePath, $extension);
        if (empty($rawRows)) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['File kosong atau format tidak dapat dibaca.']];
        }

        // Cari baris header secara fleksibel
        $headerRowIdx = self::findHeaderRowIndex($rawRows, ['domba', 'serigala', 'kategori', 'tema']);
        if ($headerRowIdx === null || ! isset($rawRows[$headerRowIdx])) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['Baris judul kolom (header) tidak ditemukan dalam file.']];
        }

        $header = $rawRows[$headerRowIdx];
        $headerMap = self::buildHeaderMap($header);

        $dataRows = array_slice($rawRows, $headerRowIdx + 1);
        if (empty($dataRows)) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['File hanya berisi baris header tanpa pasangan kata.']];
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $seenPairsInFile = [];

        foreach ($dataRows as $lineIdx => $row) {
            $rowNum = $headerRowIdx + 2 + $lineIdx;

            if (count(array_filter($row)) === 0) {
                continue;
            }

            $theme = trim(self::getVal($row, $headerMap, ['kategoritema', 'kategori', 'tema', 'theme'], 1, 'Umum'));
            $dombaWord = strtoupper(trim(self::getVal($row, $headerMap, ['katadombakataa', 'katadomba', 'dombaword', 'worda'], 2, '')));
            $dombaMeaning = trim(self::getVal($row, $headerMap, ['artikatadomba', 'artidomba', 'dombameaning', 'meaninga'], 3, ''));
            $dombaCluesRaw = trim(self::getVal($row, $headerMap, ['petunjukdomba', 'petunjukdombapisahkantitikkoma', 'dombaclues'], 4, ''));
            $serigalaWord = strtoupper(trim(self::getVal($row, $headerMap, ['kataserigala', 'kataserigalatb', 'serigalaword', 'wordb'], 5, '')));
            $serigalaMeaning = trim(self::getVal($row, $headerMap, ['artikataserigala', 'artiserigala', 'serigalameaning', 'meaningb'], 6, ''));
            $serigalaCluesRaw = trim(self::getVal($row, $headerMap, ['petunjukserigala', 'petunjukserigalapisahkantitikkoma', 'serigalaclues'], 7, ''));
            $difference = trim(self::getVal($row, $headerMap, ['penjelasanperbedaanbahasainggris', 'penjelasanperbedaan', 'perbedaan', 'englishdifference'], 8, ''));

            if (empty($dombaWord) || empty($serigalaWord)) {
                $errors[] = "Baris #{$rowNum}: Kata Domba dan Kata Serigala wajib diisi.";

                continue;
            }

            $dombaClues = self::parseClues($dombaCluesRaw, $dombaWord);
            $serigalaClues = self::parseClues($serigalaCluesRaw, $serigalaWord);

            // PERLINDUNGAN ANTI-DUPLIKAT (Cek file lokal & cek database dua arah: A vs B atau B vs A)
            $pairKey = $dombaWord.'_VS_'.$serigalaWord;
            $reversePairKey = $serigalaWord.'_VS_'.$dombaWord;
            if (isset($seenPairsInFile[$pairKey]) || isset($seenPairsInFile[$reversePairKey])) {
                $skipped++;

                continue;
            }
            $seenPairsInFile[$pairKey] = true;

            $existsInDb = WerewolfSoal::whereRaw(
                '(UPPER(TRIM(domba_word)) = ? AND UPPER(TRIM(serigala_word)) = ?) OR (UPPER(TRIM(domba_word)) = ? AND UPPER(TRIM(serigala_word)) = ?)',
                [$dombaWord, $serigalaWord, $serigalaWord, $dombaWord]
            )->exists();

            if ($existsInDb) {
                $skipped++;

                continue;
            }

            WerewolfSoal::create([
                'theme' => $theme ?: 'Umum',
                'domba_word' => $dombaWord,
                'domba_meaning' => $dombaMeaning ?: $dombaWord,
                'domba_clues' => $dombaClues,
                'serigala_word' => $serigalaWord,
                'serigala_meaning' => $serigalaMeaning ?: $serigalaWord,
                'serigala_clues' => $serigalaClues,
                'english_difference' => $difference ?: "{$dombaWord} is different from {$serigalaWord}.",
                'is_active' => true,
            ]);

            $imported++;
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'total' => $imported + $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * Import Kosakata Pelafalan (Pronounce Hero) dengan Logika Anti-Duplikat.
     */
    public static function importPronounce(string $filePath, string $extension): array
    {
        $rawRows = self::parseRows($filePath, $extension);
        if (empty($rawRows)) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['File kosong atau format tidak dapat dibaca.']];
        }

        // Cari baris header secara fleksibel
        $headerRowIdx = self::findHeaderRowIndex($rawRows, ['kosakata', 'word', 'fonetik', 'ipa', 'pelafalan']);
        if ($headerRowIdx === null || ! isset($rawRows[$headerRowIdx])) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['Baris judul kolom (header) tidak ditemukan dalam file.']];
        }

        $header = $rawRows[$headerRowIdx];
        $headerMap = self::buildHeaderMap($header);

        $dataRows = array_slice($rawRows, $headerRowIdx + 1);
        if (empty($dataRows)) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['File hanya berisi baris header tanpa kosakata.']];
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $seenWordsInFile = [];

        foreach ($dataRows as $lineIdx => $row) {
            $rowNum = $headerRowIdx + 2 + $lineIdx;

            if (count(array_filter($row)) === 0) {
                continue;
            }

            $word = trim(self::getVal($row, $headerMap, ['kosakataenglishword', 'kosakata', 'word', 'kata'], 1, ''));
            $phonetic = trim(self::getVal($row, $headerMap, ['fonetikipa', 'fonetik', 'phonetic', 'ipa'], 2, ''));
            $pos = trim(self::getVal($row, $headerMap, ['jeniskatapartofspeech', 'jeniskata', 'partofspeech', 'pos'], 3, 'Noun (Kata Benda)'));
            $meaning = trim(self::getVal($row, $headerMap, ['artikatabahasaindonesia', 'artikata', 'arti', 'meaning', 'terjemahan'], 4, ''));
            $difficulty = strtolower(trim(self::getVal($row, $headerMap, ['tingkatkesulitan', 'kesulitan', 'difficulty'], 5, 'mudah')));
            $tips = trim(self::getVal($row, $headerMap, ['tipspelafalanopsional', 'tipspelafalan', 'tips', 'petunjuk'], 6, ''));
            $example = trim(self::getVal($row, $headerMap, ['contohkalimatenglishexample', 'contohkalimat', 'examplesentence', 'contoh'], 7, ''));

            if (empty($word) || empty($meaning)) {
                $errors[] = "Baris #{$rowNum}: Kosakata dan Arti tidak boleh kosong.";

                continue;
            }

            // Normalisasi difficulty
            if (! in_array($difficulty, ['mudah', 'sedang', 'sulit'])) {
                $difficulty = 'mudah';
            }

            // Normalisasi Part of Speech
            if (! str_contains($pos, '(')) {
                $pos = match (strtolower(trim($pos))) {
                    'noun' => 'Noun (Kata Benda)',
                    'verb' => 'Verb (Kata Kerja)',
                    'adjective' => 'Adjective (Kata Sifat)',
                    'adverb' => 'Adverb (Kata Keterangan)',
                    default => $pos,
                };
            }

            // PERLINDUNGAN ANTI-DUPLIKAT (Cek file lokal & cek database)
            $upperWord = strtoupper($word);
            if (isset($seenWordsInFile[$upperWord])) {
                $skipped++;

                continue;
            }
            $seenWordsInFile[$upperWord] = true;

            $existsInDb = PronounceWord::whereRaw('UPPER(TRIM(word)) = ?', [$upperWord])->exists();
            if ($existsInDb) {
                $skipped++;

                continue;
            }

            PronounceWord::create([
                'word' => $word,
                'phonetic' => $phonetic ?: null,
                'part_of_speech' => $pos,
                'meaning' => $meaning,
                'difficulty' => $difficulty,
                'tips' => $tips ?: null,
                'example_sentence' => $example ?: null,
            ]);

            $imported++;
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'total' => $imported + $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * Import Soal Susun Kalimat (Sentence Builder) dengan Logika Anti-Duplikat.
     */
    public static function importSentence(string $filePath, string $extension): array
    {
        $rawRows = self::parseRows($filePath, $extension);
        if (empty($rawRows)) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['File kosong atau format tidak dapat dibaca.']];
        }

        // Cari baris header secara fleksibel
        $headerRowIdx = self::findHeaderRowIndex($rawRows, ['sentence', 'kalimat', 'tenses', 'grammar', 'arti']);
        if ($headerRowIdx === null || ! isset($rawRows[$headerRowIdx])) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['Baris judul kolom (header) tidak ditemukan dalam file.']];
        }

        $header = $rawRows[$headerRowIdx];
        $headerMap = self::buildHeaderMap($header);

        $dataRows = array_slice($rawRows, $headerRowIdx + 1);
        if (empty($dataRows)) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['File hanya berisi baris header tanpa kalimat.']];
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $seenSentencesInFile = [];

        foreach ($dataRows as $lineIdx => $row) {
            $rowNum = $headerRowIdx + 2 + $lineIdx;

            if (count(array_filter($row)) === 0) {
                continue;
            }

            $levelVal = self::getVal($row, $headerMap, ['level', 'tingkat'], 1, '1');
            $level = (int) preg_replace('/[^0-9]/', '', $levelVal);
            if ($level < 1 || $level > 9) {
                $level = 1;
            }

            $difficulty = strtolower(trim(self::getVal($row, $headerMap, ['tingkatkesulitan', 'kesulitan', 'difficulty'], 2, 'mudah')));
            $sentence = trim(self::getVal($row, $headerMap, ['kalimatbahasainggris', 'kalimat', 'sentence', 'fullsentence'], 3, ''));
            $meaning = trim(self::getVal($row, $headerMap, ['artibahasaindonesia', 'arti', 'meaning', 'terjemahan'], 4, ''));
            $tenses = trim(self::getVal($row, $headerMap, ['kategoritenses', 'tenses', 'kategori', 'category'], 5, 'General Grammar'));
            $tips = trim(self::getVal($row, $headerMap, ['tipsgrammaropsional', 'tipsgrammar', 'tips', 'petunjuk'], 6, ''));

            if (empty($sentence) || empty($meaning)) {
                $errors[] = "Baris #{$rowNum}: Kalimat bahasa Inggris dan Arti tidak boleh kosong.";

                continue;
            }

            if (! in_array($difficulty, ['mudah', 'sedang', 'sulit'])) {
                $difficulty = 'mudah';
            }

            // PERLINDUNGAN ANTI-DUPLIKAT (Cek file lokal & cek database)
            $upperSentence = strtoupper(trim(rtrim($sentence, '.!?')));
            if (isset($seenSentencesInFile[$upperSentence])) {
                $skipped++;

                continue;
            }
            $seenSentencesInFile[$upperSentence] = true;

            $existsInDb = SentenceSoal::whereRaw('UPPER(TRIM(sentence)) = ?', [$sentence])->exists();
            if ($existsInDb) {
                $skipped++;

                continue;
            }

            // Hitung poin berdasarkan level/kesulitan
            $points = match ($difficulty) {
                'sulit' => 180,
                'sedang' => 140,
                default => 100,
            };

            SentenceSoal::create([
                'level' => $level,
                'difficulty' => $difficulty,
                'sentence' => $sentence,
                'meaning' => $meaning,
                'tenses_category' => $tenses ?: 'General Grammar',
                'grammar_tips' => $tips ?: null,
                'points' => $points,
            ]);

            $imported++;
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'total' => $imported + $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * Parse string daftar petunjuk menjadi array (memisahkan koma, titik-koma, atau baris baru).
     */
    protected static function parseClues(string $raw, string $word): array
    {
        if (empty($raw)) {
            return [
                "It is related to {$word}.",
                'Commonly known in English.',
                'Has distinct characteristics.',
            ];
        }

        $parts = preg_split('/[;|\n]/', $raw);
        $clues = [];
        foreach ($parts as $p) {
            $clean = trim($p);
            if (! empty($clean)) {
                $clues[] = $clean;
            }
        }

        return ! empty($clues) ? $clues : ["It is related to {$word}."];
    }
}
