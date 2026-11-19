<?php

namespace App\Services;

use App\Models\FishingSoal;
use App\Models\WerewolfSoal;
use SimpleXMLElement;
use ZipArchive;

class GameExcelService
{
    /**
     * Membaca isi file Excel (.xlsx) atau file CSV (.csv) menjadi array baris data.
     */
    public static function parseRows(string $filePath, string $extension): array
    {
        $extension = strtolower($extension);

        if ($extension === 'csv' || $extension === 'txt') {
            return self::parseCsv($filePath);
        }

        if ($extension === 'xlsx') {
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
            // Bersihkan setiap kolom dari whitespace
            $cleanRow = array_map(function ($val) {
                return trim((string) $val);
            }, $data);

            // Lewati baris kosong
            if (count(array_filter($cleanRow)) === 0) {
                continue;
            }

            $rows[] = $cleanRow;
        }

        fclose($stream);

        return $rows;
    }

    /**
     * Parse file Excel .xlsx murni menggunakan ZipArchive & SimpleXML bawaan PHP.
     * Tidak membutuhkan library vendor pihak ketiga yang berat.
     */
    protected static function parseXlsx(string $filePath): array
    {
        $rows = [];
        $zip = new ZipArchive();

        if ($zip->open($filePath) !== true) {
            return $rows;
        }

        // 1. Baca shared strings (teks string pada file XLSX)
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

        // 2. Baca sheet pertama (xl/worksheets/sheet1.xml)
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
                // Baca koordinat sel seperti "A1", "B1" untuk menentukan indeks kolom
                $cellRef = (string) $cell['r'];
                preg_match('/^([A-Z]+)(\d+)$/', $cellRef, $matches);
                $colLetters = $matches[1] ?? 'A';
                $targetColIdx = self::lettersToColIndex($colLetters);

                // Isi sel kosong jika ada kolom yang dilewati
                while ($currentColIdx < $targetColIdx) {
                    $rowCells[] = '';
                    $currentColIdx++;
                }

                $cellType = (string) $cell['t'];
                $val = isset($cell->v) ? (string) $cell->v : '';

                if ($cellType === 's') {
                    // Shared string
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
     * Import Butir Soal Game Tangkap Ikan (Word Fishing) dengan Logika Anti-Duplikat (Upsert/Skip).
     */
    public static function importFishing(string $filePath, string $extension): array
    {
        $rawRows = self::parseRows($filePath, $extension);
        if (count($rawRows) <= 1) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['File kosong atau hanya berisi baris judul header.']];
        }

        // Asumsikan baris pertama adalah header
        $header = array_shift($rawRows);
        $headerMap = [];
        foreach ($header as $idx => $h) {
            $cleanH = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string) $h));
            $headerMap[$cleanH] = $idx;
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rawRows as $lineIdx => $row) {
            $rowNum = $lineIdx + 2;

            // Ambil nilai kolom berdasarkan header atau urutan indeks standar
            $level = (int) self::getVal($row, $headerMap, ['level', 'tingkat'], 0, 1);
            $difficulty = strtolower(self::getVal($row, $headerMap, ['difficulty', 'kesulitan'], 1, 'mudah'));
            $word = strtoupper(trim(self::getVal($row, $headerMap, ['word', 'kosakata', 'katabahasainggris'], 2, '')));
            $pos = self::getVal($row, $headerMap, ['partofspeech', 'jeniskata', 'pos'], 3, 'Noun (Kata Benda)');
            $meaning = trim(self::getVal($row, $headerMap, ['meaning', 'arti', 'terjemahan', 'jawabanbenar'], 4, ''));
            $opt2 = trim(self::getVal($row, $headerMap, ['option2', 'pengecoh1', 'pilihan2'], 5, ''));
            $opt3 = trim(self::getVal($row, $headerMap, ['option3', 'pengecoh2', 'pilihan3'], 6, ''));
            $opt4 = trim(self::getVal($row, $headerMap, ['option4', 'pengecoh3', 'pilihan4'], 7, ''));
            $explanation = trim(self::getVal($row, $headerMap, ['explanation', 'penjelasan'], 8, ''));
            $example = trim(self::getVal($row, $headerMap, ['examplesentence', 'contohkalimat', 'contoh'], 9, ''));

            if (empty($word) || empty($meaning)) {
                $errors[] = "Baris #{$rowNum}: Kosakata (Word) dan Arti tidak boleh kosong.";
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

            // Normalisasi Pilihan Jawaban
            $options = [$meaning];
            if (! empty($opt2)) $options[] = $opt2;
            if (! empty($opt3)) $options[] = $opt3;
            if (! empty($opt4)) $options[] = $opt4;

            // Tambahkan fallback pilihan jika guru hanya mengisi arti
            while (count($options) < 4) {
                $fallback = 'Opsi Pengecoh '.count($options);
                $options[] = $fallback;
            }

            // FILTER ANTI-DUPLIKAT: Cek apakah kosakata ini sudah pernah didaftarkan
            $exists = FishingSoal::whereRaw('UPPER(TRIM(word)) = ?', [$word])->exists();
            if ($exists) {
                $skipped++;
                continue; // Lewati agar database tetap ringan dan tidak dobel
            }

            // Simpan Soal Baru
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
        if (count($rawRows) <= 1) {
            return ['imported' => 0, 'skipped' => 0, 'total' => 0, 'errors' => ['File kosong atau hanya berisi baris judul header.']];
        }

        $header = array_shift($rawRows);
        $headerMap = [];
        foreach ($header as $idx => $h) {
            $cleanH = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string) $h));
            $headerMap[$cleanH] = $idx;
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rawRows as $lineIdx => $row) {
            $rowNum = $lineIdx + 2;

            $theme = trim(self::getVal($row, $headerMap, ['theme', 'kategori', 'tema'], 0, 'Umum'));
            $dombaWord = strtoupper(trim(self::getVal($row, $headerMap, ['dombaword', 'katadomba', 'worda', 'kataa'], 1, '')));
            $dombaMeaning = trim(self::getVal($row, $headerMap, ['dombameaning', 'artidomba', 'meaninga', 'artia'], 2, ''));
            $dombaCluesRaw = trim(self::getVal($row, $headerMap, ['dombaclues', 'petunjukdomba', 'cluesa'], 3, ''));
            $serigalaWord = strtoupper(trim(self::getVal($row, $headerMap, ['serigalaword', 'kataserigala', 'wordb', 'katab'], 4, '')));
            $serigalaMeaning = trim(self::getVal($row, $headerMap, ['serigalameaning', 'artiserigala', 'meaningb', 'artib'], 5, ''));
            $serigalaCluesRaw = trim(self::getVal($row, $headerMap, ['serigalaclues', 'petunjukserigala', 'cluesb'], 6, ''));
            $difference = trim(self::getVal($row, $headerMap, ['englishdifference', 'perbedaan', 'penjelasan'], 7, ''));

            if (empty($dombaWord) || empty($serigalaWord)) {
                $errors[] = "Baris #{$rowNum}: Kata Domba dan Kata Serigala wajib diisi.";
                continue;
            }

            // Parse clues menjadi array
            $dombaClues = self::parseClues($dombaCluesRaw, $dombaWord);
            $serigalaClues = self::parseClues($serigalaCluesRaw, $serigalaWord);

            // FILTER ANTI-DUPLIKAT: Cek apakah pasangan kata ini sudah pernah didaftarkan
            $exists = WerewolfSoal::whereRaw('UPPER(TRIM(domba_word)) = ? AND UPPER(TRIM(serigala_word)) = ?', [$dombaWord, $serigalaWord])->exists();
            if ($exists) {
                $skipped++;
                continue; // Lewati agar data tidak membengkak
            }

            // Simpan Pasangan Kata Baru
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
     * Parse string daftar petunjuk menjadi array (memisahkan koma, titik-koma, atau baris baru).
     */
    protected static function parseClues(string $raw, string $word): array
    {
        if (empty($raw)) {
            return [
                "It is related to {$word}.",
                "Commonly known in English.",
                "Has distinct characteristics.",
            ];
        }

        // Pisahkan berdasarkan delimiter koma, titik koma, atau pipe
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

    /**
     * Helper mengambil nilai dari baris array baik melalui nama header maupun urutan indeks kolom.
     */
    protected static function getVal(array $row, array $headerMap, array $keys, int $fallbackIdx, string $default = ''): string
    {
        foreach ($keys as $k) {
            if (isset($headerMap[$k]) && isset($row[$headerMap[$k]])) {
                $val = trim((string) $row[$headerMap[$k]]);
                if ($val !== '') return $val;
            }
        }

        if (isset($row[$fallbackIdx])) {
            $val = trim((string) $row[$fallbackIdx]);
            if ($val !== '') return $val;
        }

        return $default;
    }
}
