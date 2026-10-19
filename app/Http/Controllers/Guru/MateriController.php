<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\StoreMateriRequest;
use App\Http\Requests\Guru\UpdateMateriRequest;
use App\Models\KategoriMateri;
use App\Models\Materi;
use App\Models\MateriSoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar semua modul materi pembelajaran.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $query = Materi::withCount('soals')->latest();

        // Isolasi Materi: Akun guru hanya dapat mengelola materi miliknya sendiri atau materi umum
        if ($user && $user->role === 'guru') {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            });
        }

        // Filter berdasarkan Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan Tingkat Kelas
        if ($request->filled('kelas')) {
            $query->where(function ($q) use ($request) {
                $q->where('tingkat_kelas', $request->kelas)
                    ->orWhere('tingkat_kelas', 'Semua Kelas');
            });
        }

        // Filter Pencarian (Judul / Deskripsi)
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi_singkat', 'like', "%{$search}%")
                    ->orWhere('penjelasan', 'like', "%{$search}%");
            });
        }

        $materis = $query->paginate(10)->withQueryString();

        // Statistik Ringkas Berdasarkan Hak Akses Guru
        $totalQuery = Materi::query();
        $aktifQuery = Materi::where('status', 'aktif');
        if ($user && $user->role === 'guru') {
            $totalQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            });
            $aktifQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            });
        }
        $totalMateri = $totalQuery->count();
        $totalAktif = $aktifQuery->count();
        $kategoriList = KategoriMateri::where('is_aktif', true)->orderBy('urutan')->get();

        return view('guru.manajemen_materi.index', compact('materis', 'totalMateri', 'totalAktif', 'kategoriList'));
    }

    /**
     * Menampilkan formulir tambah materi baru.
     */
    public function create(): View
    {
        $kategoriList = KategoriMateri::where('is_aktif', true)->orderBy('urutan')->get();
        $kelasDiampu = auth()->user()->kelas_diampu ?? [];

        return view('guru.manajemen_materi.create', compact('kategoriList', 'kelasDiampu'));
    }

    /**
     * Menyimpan materi baru beserta butir latihan soal ke database dengan transaksi atomik.
     */
    public function store(StoreMateriRequest $request): RedirectResponse
    {
        $newFiles = [];

        try {
            DB::transaction(function () use ($request, &$newFiles) {
                $data = $request->only([
                    'judul',
                    'kategori',
                    'tingkat_kelas',
                    'target_rombel',
                    'deskripsi_singkat',
                    'penjelasan',
                    'contoh_teks',
                    'xp_reward',
                    'status',
                ]);

                $data['user_id'] = auth()->id();
                $data['xp_reward'] = $data['xp_reward'] ?? 50;

                // Upload Gambar Materi jika ada
                if ($request->hasFile('gambar_materi')) {
                    $path = $request->file('gambar_materi')->store('materi/images', 'public');
                    $data['gambar_materi'] = $path;
                    $newFiles[] = $path;
                }

                // Upload Audio Materi jika ada
                if ($request->hasFile('audio_materi')) {
                    $path = $request->file('audio_materi')->store('materi/audio', 'public');
                    $data['audio_materi'] = $path;
                    $newFiles[] = $path;
                }

                $materi = Materi::create($data);

                // Simpan Latihan Soal Pemahaman jika ada diinput
                $this->syncSoals($request, $materi, false, $newFiles);
            });
        } catch (\Throwable $e) {
            // Bersihkan file yang terlanjur terupload jika transaksi database gagal
            foreach ($newFiles as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
            throw $e;
        }

        return redirect()->route('guru.materi.index')->with('success', 'Modul materi baru berhasil disimpan dan dipublikasikan!');
    }

    /**
     * Menampilkan detail preview materi (tampilan yang dilihat siswa).
     */
    public function show(Materi $materi): View
    {
        $user = auth()->user();
        if ($user && $user->role === 'guru' && $materi->user_id && $materi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat materi guru lain.');
        }

        $materi->load('soals');

        return view('guru.manajemen_materi.show', compact('materi'));
    }

    /**
     * Menampilkan formulir edit materi.
     */
    public function edit(Materi $materi): View
    {
        $user = auth()->user();
        if ($user && $user->role === 'guru' && $materi->user_id && $materi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit materi guru lain.');
        }

        $materi->load('soals');
        $kategoriList = KategoriMateri::where('is_aktif', true)->orderBy('urutan')->get();
        $kelasDiampu = auth()->user()->kelas_diampu ?? [];

        return view('guru.manajemen_materi.edit', compact('materi', 'kategoriList', 'kelasDiampu'));
    }

    /**
     * Memperbarui data materi dan butir soal latihannya secara atomik.
     */
    public function update(UpdateMateriRequest $request, Materi $materi): RedirectResponse
    {
        $user = auth()->user();
        if ($user && $user->role === 'guru' && $materi->user_id && $materi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk memperbarui materi guru lain.');
        }

        $newFiles = [];
        $filesToDelete = [];

        try {
            DB::transaction(function () use ($request, $materi, &$newFiles, &$filesToDelete) {
                $data = $request->only([
                    'judul',
                    'kategori',
                    'tingkat_kelas',
                    'target_rombel',
                    'deskripsi_singkat',
                    'penjelasan',
                    'contoh_teks',
                    'xp_reward',
                    'status',
                ]);

                // Jika materi belum memiliki pemilik, tautkan ke guru yang mengeditnya
                if (! $materi->user_id) {
                    $data['user_id'] = auth()->id();
                }

                // Ganti gambar jika diunggah baru
                if ($request->hasFile('gambar_materi')) {
                    if ($materi->gambar_materi) {
                        $filesToDelete[] = $materi->gambar_materi;
                    }
                    $path = $request->file('gambar_materi')->store('materi/images', 'public');
                    $data['gambar_materi'] = $path;
                    $newFiles[] = $path;
                }

                // Ganti audio jika diunggah baru
                if ($request->hasFile('audio_materi')) {
                    if ($materi->audio_materi) {
                        $filesToDelete[] = $materi->audio_materi;
                    }
                    $path = $request->file('audio_materi')->store('materi/audio', 'public');
                    $data['audio_materi'] = $path;
                    $newFiles[] = $path;
                }

                $materi->update($data);

                // Hapus soal lama dan buat ulang dari form edit
                $this->syncSoals($request, $materi, true, $newFiles, $filesToDelete);
            });

            // Bersihkan file lama setelah transaksi sukses
            foreach ($filesToDelete as $oldFile) {
                if (Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
        } catch (\Throwable $e) {
            // Bersihkan file yang baru diunggah jika transaksi gagal
            foreach ($newFiles as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
            throw $e;
        }

        return redirect()->route('guru.materi.index')->with('success', 'Perubahan modul materi berhasil disimpan!');
    }

    /**
     * Menghapus materi beserta seluruh latihan soal dan file medianya secara aman.
     */
    public function destroy(Materi $materi): RedirectResponse
    {
        $user = auth()->user();
        if ($user && $user->role === 'guru' && $materi->user_id && $materi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus materi guru lain.');
        }

        $filesToDelete = [];

        if ($materi->gambar_materi) {
            $filesToDelete[] = $materi->gambar_materi;
        }
        if ($materi->audio_materi) {
            $filesToDelete[] = $materi->audio_materi;
        }
        foreach ($materi->soals as $soal) {
            if ($soal->audio_soal) {
                $filesToDelete[] = $soal->audio_soal;
            }
        }

        DB::transaction(function () use ($materi) {
            $materi->soals()->delete();
            $materi->delete();
        });

        foreach ($filesToDelete as $filePath) {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        return redirect()->route('guru.materi.index')->with('success', 'Modul materi berhasil dihapus.');
    }

    /**
     * Helper untuk memproses penyimpanan butir soal latihan pemahaman.
     *
     * @param  array<int, string>  $newFiles
     * @param  array<int, string>  $filesToDelete
     */
    private function syncSoals(
        Request $request,
        Materi $materi,
        bool $isUpdate = false,
        array &$newFiles = [],
        array &$filesToDelete = []
    ): void {
        $oldAudioPaths = $isUpdate ? $materi->soals->pluck('audio_soal')->filter()->values()->all() : [];
        $retainedAudioPaths = [];

        if ($isUpdate) {
            $materi->soals()->delete();
        }

        $soalsInput = $request->input('soals', []);

        if (is_array($soalsInput)) {
            foreach ($soalsInput as $index => $item) {
                // Abaikan jika pertanyaan kosong dan tidak ada file audio
                $hasNewAudio = $request->hasFile("soals.{$index}.audio_soal");
                // Hanya terima path audio lama milik materi ini (cegah manipulasi hidden input)
                $hasExistingAudio = ! empty($item['existing_audio_soal'])
                    && in_array($item['existing_audio_soal'], $oldAudioPaths, true);

                if (empty($item['pertanyaan']) && ! $hasNewAudio && ! $hasExistingAudio) {
                    continue;
                }

                $tipeSoal = $item['tipe_soal'] ?? 'pilgan';
                $audioSoalPath = null;

                if ($tipeSoal === 'listening') {
                    if ($hasNewAudio) {
                        $audioSoalPath = $request->file("soals.{$index}.audio_soal")->store('materi/soal_audio', 'public');
                        $newFiles[] = $audioSoalPath;
                    } elseif ($hasExistingAudio) {
                        $audioSoalPath = $item['existing_audio_soal'];
                    }

                    if ($audioSoalPath) {
                        $retainedAudioPaths[] = $audioSoalPath;
                    }
                }

                MateriSoal::create([
                    'materi_id' => $materi->id,
                    'tipe_soal' => $tipeSoal,
                    'pertanyaan' => $item['pertanyaan'] ?? ($tipeSoal === 'listening' ? 'Dengarkan rekaman audio dan jawab pertanyaan berikut:' : 'Pertanyaan evaluasi'),
                    'audio_soal' => $audioSoalPath,
                    'pilihan_a' => $item['pilihan_a'] ?? '-',
                    'pilihan_b' => $item['pilihan_b'] ?? '-',
                    'pilihan_c' => $item['pilihan_c'] ?? '-',
                    'pilihan_d' => $item['pilihan_d'] ?? '-',
                    'kunci_jawaban' => strtoupper($item['kunci_jawaban'] ?? 'A'),
                    'pembahasan' => $item['pembahasan'] ?? null,
                ]);
            }
        }

        // Tandai file audio fisik yang tidak lagi dipakai untuk dihapus
        if ($isUpdate) {
            foreach ($oldAudioPaths as $oldPath) {
                if (! in_array($oldPath, $retainedAudioPaths, true)) {
                    $filesToDelete[] = $oldPath;
                }
            }
        }
    }
}
