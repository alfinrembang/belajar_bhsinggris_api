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

        // 1. Metrik Pokok Siswa & Rombel
        $totalSiswa = Siswa::count();
        $siswaKelas10 = Siswa::where('kelas', '10')->count();
        $siswaKelas11 = Siswa::where('kelas', '11')->count();
        $siswaKelas12 = Siswa::where('kelas', '12')->count();

        // 2. Metrik Bahan Ajar & Modul
        $totalMateri = Materi::count();
        $totalKategori = KategoriMateri::count();
        $totalListening = ModulListening::count();
        $totalQuiz = Quiz::count();

        // 3. Ringkasan Ketuntasan Belajar & Rata-rata Skor
        $materiSkors = SiswaMateriProgres::whereNotNull('skor')->pluck('skor');
        $rataMateri = $materiSkors->isNotEmpty() ? round($materiSkors->avg(), 1) : 0;
        $totalMateriSelesai = SiswaMateriProgres::where('status', 'selesai')->count();

        $listeningSkors = SiswaListeningProgres::whereNotNull('skor')->pluck('skor');
        $rataListening = $listeningSkors->isNotEmpty() ? round($listeningSkors->avg(), 1) : 0;
        $totalListeningSelesai = SiswaListeningProgres::where('status', 'selesai')->count();

        $quizSkors = SiswaQuizProgres::whereNotNull('skor')->pluck('skor');
        $rataQuiz = $quizSkors->isNotEmpty() ? round($quizSkors->avg(), 1) : 0;
        $totalQuizSelesai = SiswaQuizProgres::where('status', 'selesai')->count();

        $totalAktivitasSelesai = $totalMateriSelesai + $totalListeningSelesai + $totalQuizSelesai;
        $rataKeseluruhan = round(($rataMateri * 0.3) + ($rataListening * 0.3) + ($rataQuiz * 0.4), 1);

        // 4. Siswa Paling Unggul (Top 5 Siswa dengan Akumulasi XP Tertinggi)
        $topSiswa = Siswa::query()
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
            'pengaturanAkademik'
        ));
    }
}
