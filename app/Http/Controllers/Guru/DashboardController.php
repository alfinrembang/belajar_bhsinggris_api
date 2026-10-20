<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\KategoriMateri;
use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\PengaturanAkademik;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\SiswaListeningProgres;
use App\Models\SiswaMateriProgres;
use App\Models\SiswaQuizProgres;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama guru dengan ringkasan eksekutif seluruh modul.
     */
    public function index(): View|RedirectResponse
    {
        if (auth()->user()?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $guru = auth()->user();
        $kelasDiampu = is_array($guru?->kelas_diampu) ? array_filter($guru->kelas_diampu) : [];
        $isFilterKelas = ! empty($kelasDiampu);

        // 1. Metrik Pokok Siswa & Rombel (Filter khusus kelas binaan guru jika tersedia)
        $siswaDiampuQuery = Siswa::query();
        if ($isFilterKelas) {
            $siswaDiampuQuery->where(function ($query) use ($kelasDiampu) {
                foreach ($kelasDiampu as $rombel) {
                    $parts = explode(' ', trim((string) $rombel));
                    if (count($parts) >= 3) {
                        $query->orWhere(function ($q) use ($parts) {
                            $q->where('kelas', $parts[0])
                                ->where('jurusan', $parts[1])
                                ->where('no_kelas', $parts[2]);
                        });
                    } elseif (count($parts) === 2) {
                        $query->orWhere(function ($q) use ($parts) {
                            $q->where('kelas', $parts[0])
                                ->where('jurusan', $parts[1]);
                        });
                    } elseif (count($parts) === 1 && ! empty($parts[0])) {
                        $query->orWhere('kelas', $parts[0]);
                    }
                }
            });
        }

        $siswaDiampuIds = $siswaDiampuQuery->pluck('id');
        $totalSiswaDiampu = $siswaDiampuIds->count();
        $labelKelasDiampu = $isFilterKelas ? implode(', ', $kelasDiampu) : 'Seluruh Siswa Terdaftar';

        $totalSiswa = Siswa::count();
        $siswaKelas10 = Siswa::where('kelas', '10')->count();
        $siswaKelas11 = Siswa::where('kelas', '11')->count();
        $siswaKelas12 = Siswa::where('kelas', '12')->count();

        // 2. Metrik Bahan Ajar & Modul
        $totalMateri = Materi::count();
        $totalKategori = KategoriMateri::count();
        $totalListening = ModulListening::count();
        $totalQuiz = Quiz::count();

        // 3. Ringkasan Ketuntasan Belajar & Rata-rata Skor (Dinamis sesuai siswa binaan guru)
        $materiQuery = SiswaMateriProgres::query();
        $listeningQuery = SiswaListeningProgres::query();
        $quizQuery = SiswaQuizProgres::query();

        if ($isFilterKelas) {
            $materiQuery->whereIn('siswa_id', $siswaDiampuIds);
            $listeningQuery->whereIn('siswa_id', $siswaDiampuIds);
            $quizQuery->whereIn('siswa_id', $siswaDiampuIds);
        }

        $materiSkors = (clone $materiQuery)->whereNotNull('skor')->pluck('skor');
        $rataMateri = $materiSkors->isNotEmpty() ? round($materiSkors->avg(), 1) : 0;
        $totalMateriSelesai = (clone $materiQuery)->where('status', 'selesai')->count();

        $listeningSkors = (clone $listeningQuery)->whereNotNull('skor')->pluck('skor');
        $rataListening = $listeningSkors->isNotEmpty() ? round($listeningSkors->avg(), 1) : 0;
        $totalListeningSelesai = (clone $listeningQuery)->where('status', 'selesai')->count();

        $quizSkors = (clone $quizQuery)->whereNotNull('skor')->pluck('skor');
        $rataQuiz = $quizSkors->isNotEmpty() ? round($quizSkors->avg(), 1) : 0;
        $totalQuizSelesai = (clone $quizQuery)->where('status', 'selesai')->count();

        $totalAktivitasSelesai = $totalMateriSelesai + $totalListeningSelesai + $totalQuizSelesai;
        $rataKeseluruhan = round(($rataMateri * 0.3) + ($rataListening * 0.3) + ($rataQuiz * 0.4), 1);

        // Derajat pembagian segmen Donut Chart (360 derajat)
        $bobotMateri = $rataMateri * 0.3;
        $bobotListening = $rataListening * 0.3;
        $bobotQuiz = $rataQuiz * 0.4;
        $totalBobot = $bobotMateri + $bobotListening + $bobotQuiz;

        if ($totalBobot > 0) {
            $degMateri = round(($bobotMateri / $totalBobot) * 360, 1);
            $degListening = round((($bobotMateri + $bobotListening) / $totalBobot) * 360, 1);
        } else {
            // Proporsi default berbobot jika belum ada skor (30%, 30%, 40%)
            $degMateri = 108.0;
            $degListening = 216.0;
        }

        // 4. Siswa Paling Unggul (Top 5 Siswa dengan Akumulasi XP Tertinggi)
        $topSiswaQuery = Siswa::query();
        if ($isFilterKelas) {
            $topSiswaQuery->whereIn('id', $siswaDiampuIds);
        }

        $topSiswa = $topSiswaQuery
            ->with(['materiProgres', 'listeningProgres', 'quizProgres'])
            ->get()
            ->map(function (Siswa $siswa): array {
                $totalXp = ($siswa->materiProgres->sum('xp_didapat') ?? 0)
                    + ($siswa->listeningProgres->sum('xp_didapat') ?? 0)
                    + ($siswa->quizProgres->sum('xp_didapat') ?? 0);

                $mSkor = $siswa->materiProgres->pluck('skor')->filter();
                $lSkor = $siswa->listeningProgres->pluck('skor')->filter();
                $qSkor = $siswa->quizProgres->pluck('skor')->filter();

                $rM = $mSkor->isNotEmpty() ? $mSkor->avg() : 0;
                $rL = $lSkor->isNotEmpty() ? $lSkor->avg() : 0;
                $rQ = $qSkor->isNotEmpty() ? $qSkor->avg() : 0;
                $nilaiAkhir = round(($rM * 0.3) + ($rL * 0.3) + ($rQ * 0.4), 1);

                return [
                    'id' => $siswa->id,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'kelas_lengkap' => $siswa->kelas_lengkap ?: 'Kelas '.($siswa->kelas ?? '-'),
                    'total_xp' => $totalXp,
                    'nilai_akhir' => $nilaiAkhir,
                    'avatar' => strtoupper(substr($siswa->nama_lengkap ?? 'S', 0, 1)),
                ];
            })
            ->sortByDesc('total_xp')
            ->take(5)
            ->values();

        // 5. Modul Pembelajaran Materi Terbaru (Dipaginate 5 materi per halaman)
        $materiTerbaru = Materi::latest()->paginate(5)->fragment('modul-terbaru')->withQueryString();

        // 6. Pengaturan Akademik Aktif
        $pengaturanAkademik = PengaturanAkademik::first();

        return view('guru.dashboard_utama.index', compact(
            'totalSiswa',
            'siswaKelas10',
            'siswaKelas11',
            'siswaKelas12',
            'totalMateri',
            'totalKategori',
            'totalListening',
            'totalQuiz',
            'rataMateri',
            'totalMateriSelesai',
            'rataListening',
            'totalListeningSelesai',
            'rataQuiz',
            'totalQuizSelesai',
            'totalAktivitasSelesai',
            'rataKeseluruhan',
            'topSiswa',
            'materiTerbaru',
            'pengaturanAkademik',
            'isFilterKelas',
            'labelKelasDiampu',
            'totalSiswaDiampu',
            'degMateri',
            'degListening'
        ));
    }
}
