<?php

namespace App\Http\Controllers\Guru;

use App\Exports\LaporanSiswaExport;
use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\SiswaListeningProgres;
use App\Models\SiswaMateriProgres;
use App\Models\SiswaQuizProgres;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanAnalitikController extends Controller
{
    public const KKM = 75;

    /**
     * Menampilkan dashboard Laporan & Analitik komprehensif.
     */
    public function index(Request $request): View
    {
        $semuaSiswa = $this->getProcessedSiswaCollection($request);

        // Filter status kelulusan/ketuntasan jika dipilih
        $filteredCollection = $semuaSiswa;
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'tuntas') {
                $filteredCollection = $filteredCollection->where('is_tuntas', true);
            } elseif ($status === 'remedial') {
                $filteredCollection = $filteredCollection->where('is_tuntas', false);
            }
        }

        // Pagination untuk tabel leger nilai siswa (10 per halaman)
        $perPage = 10;
        $page = (int) $request->input('page', 1);
        $paginatedSiswa = new LengthAwarePaginator(
            $filteredCollection->forPage($page, $perPage)->values(),
            $filteredCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 1. Ringkasan Metrik Global (KPI)
        $totalSiswa = $semuaSiswa->count();
        $totalTuntas = $semuaSiswa->where('is_tuntas', true)->count();
        $totalRemedial = $semuaSiswa->where('is_tuntas', false)->count();

        $rataRataKeseluruhan = $totalSiswa > 0
            ? round($semuaSiswa->avg('nilai_akhir'), 1)
            : 0;

        $persentaseKetuntasan = $totalSiswa > 0
            ? round(($totalTuntas / $totalSiswa) * 100, 1)
            : 0;

        $totalAktivitasBelajar = SiswaMateriProgres::count()
            + SiswaListeningProgres::count()
            + SiswaQuizProgres::count();

        // 2. Analisis Performa per Modul Pembelajaran
        $modulMateriSkors = SiswaMateriProgres::whereNotNull('skor')->pluck('skor');
        $rataModulMateri = $modulMateriSkors->isNotEmpty() ? round($modulMateriSkors->avg(), 1) : 0;
        $totalMasterMateri = Materi::count();
        $ketuntasanMateriPersen = ($totalSiswa > 0 && $totalMasterMateri > 0)
            ? round((SiswaMateriProgres::where('status', 'selesai')->count() / ($totalSiswa * $totalMasterMateri)) * 100, 1)
            : 0;

        $modulListeningSkors = SiswaListeningProgres::whereNotNull('skor')->pluck('skor');
        $rataModulListening = $modulListeningSkors->isNotEmpty() ? round($modulListeningSkors->avg(), 1) : 0;
        $totalMasterListening = ModulListening::count();
        $ketuntasanListeningPersen = ($totalSiswa > 0 && $totalMasterListening > 0)
            ? round((SiswaListeningProgres::where('status', 'selesai')->count() / ($totalSiswa * $totalMasterListening)) * 100, 1)
            : 0;

        $modulQuizSkors = SiswaQuizProgres::whereNotNull('skor')->pluck('skor');
        $rataModulQuiz = $modulQuizSkors->isNotEmpty() ? round($modulQuizSkors->avg(), 1) : 0;
        $totalMasterQuiz = Quiz::count();
        $ketuntasanQuizPersen = ($totalSiswa > 0 && $totalMasterQuiz > 0)
            ? round((SiswaQuizProgres::where('status', 'selesai')->count() / ($totalSiswa * $totalMasterQuiz)) * 100, 1)
            : 0;

        // 3. Komparasi Rata-rata Nilai per Rombel / Kelas
        $komparasiRombel = $semuaSiswa
            ->groupBy('kelas_lengkap')
            ->map(function ($items, $namaKelas) {
                $first = $items->first();

                return [
                    'nama_kelas' => $namaKelas ?: 'Belum diatur',
                    'kelas' => $first['kelas'] ?? '',
                    'jurusan' => $first['jurusan'] ?? '',
                    'total_siswa' => $items->count(),
                    'rata_nilai' => round($items->avg('nilai_akhir'), 1),
                    'total_tuntas' => $items->where('is_tuntas', true)->count(),
                ];
            })
            ->sortByDesc('rata_nilai');

        // 4. Siswa Berprestasi Tertinggi (Top 3) & Siswa Perlu Remedial
        $topSiswa = $semuaSiswa->sortByDesc('nilai_akhir')->take(3);
        $remedialSiswa = $semuaSiswa->where('is_tuntas', false)->sortBy('nilai_akhir')->take(5);

        $daftarJurusan = ['RPL', 'DKV', 'TSM', 'BD', 'SA', 'MPLB', 'TKKR'];
        $kkm = self::KKM;

        return view('guru.laporan_analitik.index', compact(
            'paginatedSiswa',
            'totalSiswa',
            'rataRataKeseluruhan',
            'persentaseKetuntasan',
            'totalAktivitasBelajar',
            'totalRemedial',
            'rataModulMateri',
            'ketuntasanMateriPersen',
            'rataModulListening',
            'ketuntasanListeningPersen',
            'rataModulQuiz',
            'ketuntasanQuizPersen',
            'komparasiRombel',
            'topSiswa',
            'remedialSiswa',
            'daftarJurusan',
            'kkm'
        ));
    }

    /**
     * Mengekspor rekapitulasi nilai kolektif siswa ke format Excel (.xlsx) resmi dengan styling rapi.
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $siswaCollection = $this->getProcessedSiswaCollection($request);
        $kkm = self::KKM;

        $totalSiswa = $siswaCollection->count();
        $totalTuntas = $siswaCollection->where('is_tuntas', true)->count();
        $totalRemedial = $siswaCollection->where('is_tuntas', false)->count();
        $rataRataKelas = $totalSiswa > 0 ? round($siswaCollection->avg('nilai_akhir'), 1) : 0;

        $filename = 'rekap_nilai_siswa_'.date('Y-m-d_His').'.xlsx';

        return Excel::download(
            new LaporanSiswaExport(
                $siswaCollection,
                $kkm,
                $totalSiswa,
                $totalTuntas,
                $totalRemedial,
                $rataRataKelas
            ),
            $filename
        );
    }

    /**
     * Mengekspor rekapitulasi nilai kolektif siswa ke format CSV (kompatibel penuh dengan Microsoft Excel).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $siswaCollection = $this->getProcessedSiswaCollection($request);

        $filename = 'rekap_nilai_siswa_'.date('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($siswaCollection): void {
            $handle = fopen('php://output', 'w');

            // Tambahkan UTF-8 Byte Order Mark (BOM) agar simbol teks terbuka dengan benar di Microsoft Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // Header Kolom CSV
            fputcsv($handle, [
                'No',
                'Nama Lengkap Siswa',
                'NIS',
                'NISN',
                'Rombel Kelas',
                'Rerata Modul Materi (30%)',
                'Rerata Listening Audio (30%)',
                'Rerata Kuis & Ujian (40%)',
                'Nilai Akhir Rapor',
                'Status Ketuntasan (KKM 75)',
                'Total XP',
            ]);

            foreach ($siswaCollection as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item['nama_lengkap'],
                    $item['nis'] ?: '-',
                    $item['nisn'] ?: '-',
                    $item['kelas_lengkap'],
                    $item['rata_materi'],
                    $item['rata_listening'],
                    $item['rata_quiz'],
                    $item['nilai_akhir'],
                    $item['is_tuntas'] ? 'Tuntas' : 'Remedial',
                    $item['total_xp'],
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Menampilkan lembar leger cetak rapor resmi siap print (Print-Ready Layout).
     */
    public function cetak(Request $request): View
    {
        $siswaCollection = $this->getProcessedSiswaCollection($request);
        $kkm = self::KKM;

        $totalSiswa = $siswaCollection->count();
        $totalTuntas = $siswaCollection->where('is_tuntas', true)->count();
        $totalRemedial = $siswaCollection->where('is_tuntas', false)->count();
        $rataRataKelas = $totalSiswa > 0 ? round($siswaCollection->avg('nilai_akhir'), 1) : 0;

        return view('guru.laporan_analitik.cetak', compact(
            'siswaCollection',
            'kkm',
            'totalSiswa',
            'totalTuntas',
            'totalRemedial',
            'rataRataKelas'
        ));
    }

    /**
     * Helper privat untuk memproses agregasi nilai seluruh siswa dengan filter pencarian & rombel.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function getProcessedSiswaCollection(Request $request): Collection
    {
        $query = Siswa::query()->with([
            'materiProgres',
            'listeningProgres',
            'quizProgres',
        ]);

        // Filter kata kunci pencarian
        if ($request->filled('q')) {
            $keyword = trim((string) $request->input('q'));
            $query->where(function ($q) use ($keyword): void {
                $q->where('nama_lengkap', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('nis', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%");
            });
        }

        // Filter tingkat kelas (10, 11, 12)
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->input('kelas'));
        }

        // Filter jurusan
        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->input('jurusan'));
        }

        return $query->get()->map(function (Siswa $siswa): array {
            $materiSkors = $siswa->materiProgres->pluck('skor')->filter();
            $rataMateri = $materiSkors->isNotEmpty() ? round($materiSkors->avg(), 1) : 0;

            $listeningSkors = $siswa->listeningProgres->pluck('skor')->filter();
            $rataListening = $listeningSkors->isNotEmpty() ? round($listeningSkors->avg(), 1) : 0;

            $quizSkors = $siswa->quizProgres->pluck('skor')->filter();
            $rataQuiz = $quizSkors->isNotEmpty() ? round($quizSkors->avg(), 1) : 0;

            // Bobot Nilai Akhir: 30% Materi + 30% Listening + 40% Quiz
            $nilaiAkhir = round(($rataMateri * 0.3) + ($rataListening * 0.3) + ($rataQuiz * 0.4), 1);

            $totalXp = ($siswa->materiProgres->sum('xp_didapat') ?? 0)
                + ($siswa->listeningProgres->sum('xp_didapat') ?? 0)
                + ($siswa->quizProgres->sum('xp_didapat') ?? 0);

            $isTuntas = $nilaiAkhir >= self::KKM;

            return [
                'id' => $siswa->id,
                'nama_lengkap' => $siswa->nama_lengkap,
                'email' => $siswa->email,
                'nis' => $siswa->nis,
                'nisn' => $siswa->nisn,
                'kelas' => $siswa->kelas,
                'jurusan' => $siswa->jurusan,
                'no_kelas' => $siswa->no_kelas,
                'no_absen' => $siswa->no_absen,
                'kelas_lengkap' => $siswa->kelas_lengkap,
                'rata_materi' => $rataMateri,
                'rata_listening' => $rataListening,
                'rata_quiz' => $rataQuiz,
                'nilai_akhir' => $nilaiAkhir,
                'total_xp' => $totalXp,
                'is_tuntas' => $isTuntas,
            ];
        });
    }
}
