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
use App\Models\SiswaFishingProgres;
use App\Models\SiswaListeningProgres;
use App\Models\SiswaMateriProgres;
use App\Models\SiswaQuizProgres;
use App\Services\SiswaImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        // 2. Filter Rombel Kelas Terpadu (Dinamis Berdasarkan Data Siswa Nyata)
        if ($request->filled('rombel')) {
            $rombel = trim((string) $request->input('rombel'));
            if (str_starts_with($rombel, 'tingkat:')) {
                $tingkat = str_replace('tingkat:', '', $rombel);
                $query->where('kelas', $tingkat);
            } else {
                $parts = explode(' ', $rombel);
                if (count($parts) >= 3) {
                    $query->where('kelas', $parts[0])
                        ->where('jurusan', $parts[1])
                        ->where('no_kelas', $parts[2]);
                } elseif (count($parts) === 2) {
                    $query->where('kelas', $parts[0])
                        ->where('jurusan', $parts[1]);
                } elseif (! empty($parts[0])) {
                    $query->where('kelas', $parts[0]);
                }
            }
        } elseif ($request->filled('kelas')) {
            $query->where('kelas', $request->input('kelas'));
        }

        // 3. Filter Jurusan (Kompatibilitas query langsung)
        if ($request->filled('jurusan') && ! $request->filled('rombel')) {
            $query->where('jurusan', $request->input('jurusan'));
        }

        $daftarSiswa = $query->latest()->paginate(10)->withQueryString();

        // Statistik Ringkasan Atas
        $totalSiswa = Siswa::count();
        $totalRombel = Siswa::getDaftarRombel()->count();

        // Hitung siswa yang pernah login / aktif belajar
        $siswaAktif = Siswa::whereNotNull('api_token')
            ->orWhereHas('materiProgres')
            ->orWhereHas('quizProgres')
            ->count();

        // Data filter dinamis mengikuti database aktual
        $daftarRombelGrouped = Siswa::getDaftarRombelGrouped();
        $daftarJurusan = Siswa::getDaftarJurusan();
        $daftarKelas = Siswa::getDaftarKelas();

        return view('guru.manajemen_siswa_kelas.index', compact(
            'daftarSiswa',
            'totalSiswa',
            'totalRombel',
            'siswaAktif',
            'daftarRombelGrouped',
            'daftarJurusan',
            'daftarKelas'
        ));
    }

    /**
     * Menampilkan formulir pendaftaran siswa baru.
     */
    public function create(): View
    {
        $daftarTingkat = Siswa::getDaftarKelas();
        $daftarJurusan = Siswa::getDaftarJurusan();
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
     * Mengunduh dokumen template Excel data siswa siap pakai untuk guru.
     */
    public function downloadTemplateExcel(SiswaImportService $service): StreamedResponse
    {
        $spreadsheet = $service->generateTemplate();
        $filename = 'template_import_siswa_'.date('Ymd_His').'.xlsx';

        return response()->streamDownload(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Memproses upload file Excel/CSV siswa, mengimpor akun, dan menyinkronkan nomor absen otomatis.
     */
    public function importExcel(Request $request, SiswaImportService $service): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ], [
            'file.required' => 'Silakan pilih file dokumen Excel atau CSV terlebih dahulu.',
            'file.file' => 'File yang diunggah tidak valid.',
            'file.mimes' => 'Format file harus berupa dokumen Excel (.xlsx, .xls) atau CSV (.csv).',
            'file.max' => 'Ukuran file dokumen tidak boleh melebihi 5 MB.',
        ]);

        try {
            $result = $service->import($request->file('file'));

            if (! $result['success'] && empty($result['imported']) && empty($result['updated'])) {
                $errorMsg = ! empty($result['errors']) ? implode(' ', $result['errors']) : 'Tidak ada data siswa yang valid untuk diimpor.';

                return redirect()->route('guru.siswa.index')
                    ->with('error', "Gagal mengimpor file: {$errorMsg}");
            }

            $msg = "Berhasil memproses import Excel: {$result['imported']} siswa baru ditambahkan";
            if ($result['updated'] > 0) {
                $msg .= ", {$result['updated']} data siswa diperbarui";
            }
            $msg .= ', serta nomor urut absen seluruh rombel telah otomatis diurutkan sesuai abjad nama.';

            if ($result['skipped'] > 0) {
                $msg .= " Catatan: {$result['skipped']} baris dilewati karena format tidak lengkap.";
            }

            return redirect()->route('guru.siswa.index')->with('success', $msg);
        } catch (\Throwable $e) {
            return redirect()->route('guru.siswa.index')
                ->with('error', 'Terjadi kesalahan saat membaca file Excel: '.$e->getMessage());
        }
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
        $daftarTingkat = Siswa::getDaftarKelas();
        $daftarJurusan = Siswa::getDaftarJurusan();
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
            $siswa->fishingProgres()->delete();
            $siswa->delete();
        });

        return redirect()->route('guru.siswa.index')
            ->with('success', "Akun siswa {$namaSiswa} beserta seluruh riwayat belajarnya berhasil dihapus.");
    }

    /**
     * Menghapus beberapa akun siswa sekaligus beserta riwayat progres belajarnya (Bulk Delete).
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['required', 'integer', 'exists:siswas,id'],
        ]);

        $siswaIds = $validated['siswa_ids'];
        $count = count($siswaIds);

        DB::transaction(function () use ($siswaIds) {
            SiswaMateriProgres::whereIn('siswa_id', $siswaIds)->delete();
            SiswaListeningProgres::whereIn('siswa_id', $siswaIds)->delete();
            SiswaQuizProgres::whereIn('siswa_id', $siswaIds)->delete();
            SiswaFishingProgres::whereIn('siswa_id', $siswaIds)->delete();
            Siswa::whereIn('id', $siswaIds)->delete();
        });

        return redirect()->route('guru.siswa.index')
            ->with('success', "Berhasil menghapus {$count} akun siswa terpilih beserta seluruh riwayat belajarnya.");
    }
}
