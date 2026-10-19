<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\StoreSiswaRequest;
use App\Http\Requests\Guru\UpdateSiswaRequest;
use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\PengaturanAkademik;
use App\Models\Quiz;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar seluruh akun siswa dengan pencarian dan filter kelas.
     */
    public function index(Request $request): View
    {
        $query = Siswa::query();

        // 1. Pencarian Kata Kunci (Nama, Email, NIS, NISN)
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_lengkap', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('nis', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%");
            });
        }

        // 2. Filter Tingkat Kelas (10, 11, 12)
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->input('kelas'));
        }

        // 3. Filter Jurusan
        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->input('jurusan'));
        }

        $daftarSiswa = $query->latest()->paginate(10)->withQueryString();

        // Statistik Ringkasan Atas
        $totalSiswa = Siswa::count();
        $totalRombel = Siswa::select('kelas', 'jurusan', 'no_kelas')
            ->distinct()
            ->get()
            ->map->kelas_lengkap
            ->filter()
            ->unique()
            ->count();

        // Hitung siswa yang pernah login / aktif belajar
        $siswaAktif = Siswa::whereNotNull('api_token')
            ->orWhereHas('materiProgres')
            ->orWhereHas('quizProgres')
            ->count();

        $daftarJurusan = ['RPL', 'DKV', 'TSM', 'BD', 'SA', 'MPLB', 'TKKR'];

        return view('guru.manajemen_siswa_kelas.index', compact(
            'daftarSiswa',
            'totalSiswa',
            'totalRombel',
            'siswaAktif',
            'daftarJurusan'
        ));
    }

    /**
     * Menampilkan formulir pendaftaran siswa baru.
     */
    public function create(): View
    {
        $daftarTingkat = ['10', '11', '12'];
        $daftarJurusan = ['RPL', 'DKV', 'TSM', 'BD', 'SA', 'MPLB', 'TKKR'];
        $daftarNoKelas = ['1', '2', '3'];

        return view('guru.manajemen_siswa_kelas.create', compact(
            'daftarTingkat',
            'daftarJurusan',
            'daftarNoKelas'
        ));
    }

    /**
     * Menyimpan data siswa baru ke database.
     */
    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Gunakan password default jika dikosongkan
        $defaultPassword = ! empty($data['password']) ? $data['password'] : '12345678';
        $data['password'] = Hash::make($defaultPassword);

        if (empty($data['tahun_ajaran_aktif'])) {
            $data['tahun_ajaran_aktif'] = PengaturanAkademik::getActiveSetting()->tahun_ajaran;
        }

        $siswa = Siswa::create($data);

        return redirect()->route('guru.siswa.index')
            ->with('success', "Akun siswa {$siswa->nama_lengkap} berhasil ditambahkan! Kata sandi bawaan: {$defaultPassword}");
    }

    /**
     * Menampilkan profil detail siswa & buku rapor / transkrip nilai per modul.
     */
    public function show(Siswa $siswa): View
    {
        // Eager load seluruh relasi progres belajar siswa
        $siswa->load([
            'materiProgres.materi',
            'listeningProgres.modulListening',
            'quizProgres.quiz',
        ]);

        // Ambil seluruh master materi, listening, dan kuis yang tersedia
        $semuaMateri = Materi::orderBy('id', 'asc')->get();
        $semuaListening = ModulListening::latest()->get();
        $semuaQuiz = Quiz::latest()->get();

        // Buat mapping progres agar mudah dicek per ID materi/listening/quiz
        $materiProgresMap = $siswa->materiProgres->keyBy('materi_id');
        $listeningProgresMap = $siswa->listeningProgres->keyBy('modul_listening_id');
        $quizProgresMap = $siswa->quizProgres->keyBy('quiz_id');

        // Hitung rata-rata nilai
        $materiSkors = $siswa->materiProgres->pluck('skor')->filter();
        $rataMateri = $materiSkors->isNotEmpty() ? round($materiSkors->avg()) : 0;

        $listeningSkors = $siswa->listeningProgres->pluck('skor')->filter();
        $rataListening = $listeningSkors->isNotEmpty() ? round($listeningSkors->avg()) : 0;

        $quizSkors = $siswa->quizProgres->pluck('skor')->filter();
        $rataQuiz = $quizSkors->isNotEmpty() ? round($quizSkors->avg()) : 0;

        // Total XP terkumpul
        $totalXp = ($siswa->materiProgres->sum('xp_didapat') ?? 0)
            + ($siswa->listeningProgres->sum('xp_didapat') ?? 0)
            + ($siswa->quizProgres->sum('xp_didapat') ?? 0);

        return view('guru.manajemen_siswa_kelas.show', compact(
            'siswa',
            'semuaMateri',
            'semuaListening',
            'semuaQuiz',
            'materiProgresMap',
            'listeningProgresMap',
            'quizProgresMap',
            'rataMateri',
            'rataListening',
            'rataQuiz',
            'totalXp'
        ));
    }

    /**
     * Menampilkan formulir edit profil dan rombel kelas siswa.
     */
    public function edit(Siswa $siswa): View
    {
        $daftarTingkat = ['10', '11', '12'];
        $daftarJurusan = ['RPL', 'DKV', 'TSM', 'BD', 'SA', 'MPLB', 'TKKR'];
        $daftarNoKelas = ['1', '2', '3'];

        return view('guru.manajemen_siswa_kelas.edit', compact(
            'siswa',
            'daftarTingkat',
            'daftarJurusan',
            'daftarNoKelas'
        ));
    }

    /**
     * Menyimpan perubahan data siswa.
     */
    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $siswa->update($data);

        return redirect()->route('guru.siswa.index')
            ->with('success', "Data siswa {$siswa->nama_lengkap} berhasil diperbarui.");
    }

    /**
     * Reset password cepat siswa ke sandi default: 12345678.
     */
    public function resetPassword(Siswa $siswa): RedirectResponse
    {
        $siswa->password = Hash::make('12345678');
        $siswa->save();

        return redirect()->back()
            ->with('success', "Kata sandi siswa {$siswa->nama_lengkap} berhasil di-reset menjadi default: 12345678");
    }

    /**
     * Menghapus data akun siswa beserta riwayat progres belajarnya.
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        $namaSiswa = $siswa->nama_lengkap;

        DB::transaction(function () use ($siswa) {
            $siswa->materiProgres()->delete();
            $siswa->listeningProgres()->delete();
            $siswa->quizProgres()->delete();
            $siswa->delete();
        });

        return redirect()->route('guru.siswa.index')
            ->with('success', "Akun siswa {$namaSiswa} beserta seluruh riwayat belajarnya berhasil dihapus.");
    }
}
