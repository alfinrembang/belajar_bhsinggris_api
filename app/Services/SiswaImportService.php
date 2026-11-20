<?php

namespace App\Services;

use App\Models\PengaturanAkademik;
use App\Models\Siswa;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SiswaImportService
{
    /**
     * Memproses import file Excel (.xlsx, .xls) atau CSV data siswa.
     *
     * @return array{
     *     success: bool,
     *     total_rows: int,
     *     imported: int,
     *     updated: int,
     *     skipped: int,
     *     rombel_affected: int,
     *     errors: list<string>
     * }
     */
    public function import(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        if (empty($rows)) {
            return [
                'success' => false,
                'total_rows' => 0,
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'rombel_affected' => 0,
                'errors' => ['File Excel kosong atau tidak memiliki data.'],
            ];
        }

        // 1. Cari baris header (baris yang memuat 'nama' dan 'nisn')
        $headerRowIndex = null;
        $headerMap = [];

        foreach ($rows as $rowIndex => $columns) {
            $normalizedCols = array_map(function ($val) {
                return strtolower(trim((string) $val));
            }, $columns);

            $hasNama = false;
            $hasNisn = false;

            foreach ($normalizedCols as $colKey => $colVal) {
                if (str_contains($colVal, 'nama')) {
                    $hasNama = true;
                }
                if (str_contains($colVal, 'nisn') || str_contains($colVal, 'nis')) {
                    $hasNisn = true;
                }
            }

            if ($hasNama && $hasNisn) {
                $headerRowIndex = $rowIndex;
                $headerMap = $this->buildHeaderMap($normalizedCols);
                break;
            }
        }

        // Jika tidak ditemukan baris berheader khusus, asumsikan baris 1 adalah header standar
        if ($headerRowIndex === null) {
            $headerRowIndex = array_key_first($rows);
            $normalizedCols = array_map(function ($val) {
                return strtolower(trim((string) $val));
            }, $rows[$headerRowIndex]);
            $headerMap = $this->buildHeaderMap($normalizedCols);
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $affectedRombels = [];

        $tahunAjaranAktif = PengaturanAkademik::getActiveSetting()->tahun_ajaran ?? '2024/2025';
        $defaultHashedPassword = Hash::make('12345678');

        DB::transaction(function () use (
            $rows,
            $headerRowIndex,
            $headerMap,
            $tahunAjaranAktif,
            $defaultHashedPassword,
            &$imported,
            &$updated,
            &$skipped,
            &$errors,
            &$affectedRombels
        ) {
            foreach ($rows as $rowIndex => $colValues) {
                if ($rowIndex <= $headerRowIndex) {
                    continue;
                }

                // Ambil nilai per kolom berdasarkan headerMap
                $nama = isset($headerMap['nama']) ? trim((string) ($colValues[$headerMap['nama']] ?? '')) : '';
                $nisn = isset($headerMap['nisn']) ? trim((string) ($colValues[$headerMap['nisn']] ?? '')) : '';
                $nis = isset($headerMap['nis']) ? trim((string) ($colValues[$headerMap['nis']] ?? '')) : null;
                $rombelStr = isset($headerMap['rombel']) ? trim((string) ($colValues[$headerMap['rombel']] ?? '')) : '';
                $kelasCol = isset($headerMap['kelas']) ? trim((string) ($colValues[$headerMap['kelas']] ?? '')) : '';
                $jurusanCol = isset($headerMap['jurusan']) ? trim((string) ($colValues[$headerMap['jurusan']] ?? '')) : '';
                $noKelasCol = isset($headerMap['no_kelas']) ? trim((string) ($colValues[$headerMap['no_kelas']] ?? '')) : '';

                // Abaikan jika seluruh baris kosong
                if (empty($nama) && empty($nisn) && empty($rombelStr) && empty($kelasCol)) {
                    continue;
                }

                // Validasi data minimum
                if (empty($nama)) {
                    $errors[] = "Baris {$rowIndex}: Nama siswa kosong, baris dilewati.";
                    $skipped++;

                    continue;
                }

                if (empty($nisn)) {
                    $errors[] = "Baris {$rowIndex}: NISN untuk siswa '{$nama}' kosong, baris dilewati.";
                    $skipped++;

                    continue;
                }

                // Bersihkan NISN (hapus spasi, petik, atau karakter non-numerik)
                $cleanNisn = preg_replace('/[^0-9]/', '', $nisn);
                if (empty($cleanNisn)) {
                    $cleanNisn = $nisn; // Fallback jika NISN berisi karakter khusus
                }

                // Parse rombel kelas
                $parsedRombel = $this->resolveRombel($rombelStr, $kelasCol, $jurusanCol, $noKelasCol);

                // Catat rombel yang terpengaruh untuk penomoran absen otomatis nanti
                $rombelKey = "{$parsedRombel['kelas']}|{$parsedRombel['jurusan']}|{$parsedRombel['no_kelas']}";
                $affectedRombels[$rombelKey] = $parsedRombel;

                // Cari apakah siswa dengan NISN ini sudah terdaftar
                $existing = Siswa::where('nisn', $cleanNisn)->first();

                if ($existing) {
                    $existing->update([
                        'nama_lengkap' => $nama,
                        'nis' => ! empty($nis) ? $nis : $existing->nis,
                        'kelas' => $parsedRombel['kelas'],
                        'jurusan' => $parsedRombel['jurusan'],
                        'no_kelas' => $parsedRombel['no_kelas'],
                        'tahun_ajaran_aktif' => $tahunAjaranAktif,
                        'status_kelulusan' => 'aktif',
                    ]);
                    $updated++;
                } else {
                    Siswa::create([
                        'nama_lengkap' => $nama,
                        'nisn' => $cleanNisn,
                        'nis' => ! empty($nis) ? $nis : null,
                        'kelas' => $parsedRombel['kelas'],
                        'jurusan' => $parsedRombel['jurusan'],
                        'no_kelas' => $parsedRombel['no_kelas'],
                        'no_absen' => null, // Akan otomatis diurutkan sesuai abjad di bawah
                        'password' => $defaultHashedPassword,
                        'tahun_ajaran_aktif' => $tahunAjaranAktif,
                        'status_kelulusan' => 'aktif',
                        'koin' => 0,
                    ]);
                    $imported++;
                }
            }

            // 2. Sinkronisasi Penomoran Absen Otomatis Per Rombel Sesuai Abjad (A-Z)
            foreach ($affectedRombels as $rombel) {
                $this->reorderNoAbsen(
                    $rombel['kelas'],
                    $rombel['jurusan'],
                    $rombel['no_kelas']
                );
            }
        });

        return [
            'success' => ($imported + $updated) > 0,
            'total_rows' => $imported + $updated + $skipped,
            'imported' => $imported,
            'updated' => $updated,
            'skipped' => $skipped,
            'rombel_affected' => count($affectedRombels),
            'errors' => $errors,
        ];
    }

    /**
     * Memetakan nama kolom ke huruf kolom Excel (misal: 'nama' => 'B').
     *
     * @param  array<string, string>  $cols
     * @return array<string, string>
     */
    protected function buildHeaderMap(array $cols): array
    {
        $map = [];

        foreach ($cols as $colLetter => $rawHeader) {
            $header = strtolower(trim($rawHeader));

            if (empty($header)) {
                continue;
            }

            if (! isset($map['nama']) && (str_contains($header, 'nama') || str_contains($header, 'name') || str_contains($header, 'siswa'))) {
                $map['nama'] = $colLetter;
            } elseif (! isset($map['nisn']) && str_contains($header, 'nisn')) {
                $map['nisn'] = $colLetter;
            } elseif (! isset($map['nis']) && str_contains($header, 'nis') && ! str_contains($header, 'nisn')) {
                $map['nis'] = $colLetter;
            } elseif (! isset($map['rombel']) && (str_contains($header, 'rombel') || str_contains($header, 'kelas') || str_contains($header, 'class'))) {
                $map['rombel'] = $colLetter;
            } elseif (! isset($map['jurusan']) && (str_contains($header, 'jurusan') || str_contains($header, 'program'))) {
                $map['jurusan'] = $colLetter;
            } elseif (! isset($map['no_kelas']) && (str_contains($header, 'no_kelas') || str_contains($header, 'sub_kelas') || str_contains($header, 'rombel_ke'))) {
                $map['no_kelas'] = $colLetter;
            }
        }

        // Fallback jika tidak terdeteksi kolom rombel
        if (! isset($map['rombel']) && isset($map['kelas'])) {
            $map['rombel'] = $map['kelas'];
        }

        return $map;
    }

    /**
     * Mengurai string rombel kelas gabungan maupun terpisah menjadi array struktur:
     * [kelas => '10', jurusan => 'RPL', no_kelas => '1'].
     *
     * @return array{kelas: string, jurusan: ?string, no_kelas: ?string}
     */
    public function resolveRombel(?string $rombelStr, ?string $kelasCol, ?string $jurusanCol, ?string $noKelasCol): array
    {
        // Jika sudah ada kolom jurusan/no_kelas terpisah
        if (! empty($jurusanCol) || ! empty($noKelasCol)) {
            $tingkat = $this->normalizeTingkatKelas($kelasCol ?: $rombelStr);

            return [
                'kelas' => $tingkat ?: '10',
                'jurusan' => ! empty($jurusanCol) ? strtoupper(trim($jurusanCol)) : null,
                'no_kelas' => ! empty($noKelasCol) ? trim($noKelasCol) : null,
            ];
        }

        $source = trim((string) ($rombelStr ?: $kelasCol));
        if (empty($source)) {
            return [
                'kelas' => '10',
                'jurusan' => null,
                'no_kelas' => null,
            ];
        }

        // Bersihkan tanda hubung atau underscore menjadi spasi
        $cleaned = preg_replace('/[-_]/', ' ', $source);
        $tokens = array_values(array_filter(explode(' ', (string) $cleaned)));

        if (empty($tokens)) {
            return [
                'kelas' => '10',
                'jurusan' => null,
                'no_kelas' => null,
            ];
        }

        // Token 0: Tingkat kelas (misal "10", "X", "XI", "12")
        $tingkat = $this->normalizeTingkatKelas($tokens[0]);

        $jurusan = null;
        $noKelas = null;

        if (count($tokens) === 2) {
            // Contoh "10 RPL" atau "10 1"
            if (is_numeric($tokens[1])) {
                $noKelas = $tokens[1];
            } else {
                $jurusan = strtoupper($tokens[1]);
            }
        } elseif (count($tokens) >= 3) {
            // Contoh "10 RPL 1"
            $jurusan = strtoupper($tokens[1]);
            $noKelas = $tokens[2];
        }

        return [
            'kelas' => $tingkat ?: '10',
            'jurusan' => $jurusan,
            'no_kelas' => $noKelas,
        ];
    }

    /**
     * Normalisasi angka tingkat kelas (misal 'X' -> '10', 'XI' -> '11', 'XII' -> '12').
     */
    protected function normalizeTingkatKelas(?string $raw): string
    {
        $val = strtoupper(trim((string) $raw));

        if ($val === 'X') {
            return '10';
        }
        if ($val === 'XI') {
            return '11';
        }
        if ($val === 'XII') {
            return '12';
        }

        // Ambil hanya digit angka jika ada string seperti "Kelas 10"
        preg_match('/\d+/', $val, $matches);
        if (! empty($matches[0])) {
            return $matches[0];
        }

        return ! empty($val) ? $val : '10';
    }

    /**
     * Mengurutkan ulang nomor absen (no_absen: 1, 2, 3...) per rombel kelas berdasarkan abjad nama.
     */
    public function reorderNoAbsen(string $kelas, ?string $jurusan, ?string $noKelas): void
    {
        $query = Siswa::where('kelas', $kelas)
            ->where('status_kelulusan', 'aktif');

        if ($jurusan !== null && $jurusan !== '') {
            $query->where('jurusan', $jurusan);
        } else {
            $query->whereNull('jurusan');
        }

        if ($noKelas !== null && $noKelas !== '') {
            $query->where('no_kelas', $noKelas);
        } else {
            $query->whereNull('no_kelas');
        }

        // Urutkan siswa berdasarkan nama lengkap dari A ke Z
        $siswas = $query->orderBy('nama_lengkap', 'asc')->get();

        $absen = 1;
        foreach ($siswas as $siswa) {
            $expectedAbsen = (string) $absen;
            if ($siswa->no_absen !== $expectedAbsen) {
                $siswa->no_absen = $expectedAbsen;
                $siswa->saveQuietly();
            }
            $absen++;
        }
    }

    /**
     * Menghasilkan dokumen template Excel (.xlsx) siap pakai untuk guru.
     */
    public function generateTemplate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        // Header Kolom
        $headers = [
            'A1' => 'NO',
            'B1' => 'NAMA LENGKAP',
            'C1' => 'NISN',
            'D1' => 'ROMBEL KELAS',
            'E1' => 'NIS (OPSIONAL)',
        ];

        foreach ($headers as $cell => $title) {
            $sheet->setCellValue($cell, $title);
        }

        // Format Style Header (#4EA87D background, teks putih tebal)
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Segoe UI',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4EA87D'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '3B8361'],
                ],
            ],
        ];

        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Contoh Data Baris
        $sampleData = [
            [1, 'Ahmad Fajar Pratama', '0071234561', '10 RPL 1', '10001'],
            [2, 'Alfin Rembang', '0071234562', '10 RPL 1', '10002'],
            [3, 'Budi Santoso', '0071234563', '10 RPL 1', '10003'],
            [4, 'Citra Lestari', '0071234564', '11 DKV 2', '10004'],
            [5, 'Doni Hermawan', '0071234565', '11 DKV 2', '10005'],
        ];

        $rowIdx = 2;
        foreach ($sampleData as $row) {
            $sheet->setCellValue('A'.$rowIdx, $row[0]);
            $sheet->setCellValueExplicit('B'.$rowIdx, $row[1], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C'.$rowIdx, $row[2], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D'.$rowIdx, $row[3], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E'.$rowIdx, $row[4], DataType::TYPE_STRING);

            $sheet->getRowDimension($rowIdx)->setRowHeight(22);
            $rowIdx++;
        }

        // Border tipis untuk data
        $dataBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A2:E'.($rowIdx - 1))->applyFromArray($dataBorderStyle);

        // Center untuk No, NISN, Rombel, NIS
        $sheet->getStyle('A2:A'.($rowIdx - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C2:E'.($rowIdx - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Auto width kolom
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $spreadsheet;
    }
}
