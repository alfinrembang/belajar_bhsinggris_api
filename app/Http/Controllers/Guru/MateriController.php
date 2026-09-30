<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\KategoriMateri;
use App\Models\Materi;
use App\Models\MateriSoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar semua modul materi pembelajaran.
     */
    public function index(Request $request): View
    {
        $query = Materi::withCount('soals')->latest();

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

        // Statistik Ringkas
        $totalMateri = Materi::count();
        $totalAktif = Materi::where('status', 'aktif')->count();
        $kategoriList = KategoriMateri::where('is_aktif', true)->orderBy('urutan')->get();

        return view('guru.manajemen_materi.index', compact('materis', 'totalMateri', 'totalAktif', 'kategoriList'));
    }

    /**
     * Menampilkan formulir tambah materi baru.
     */
    public function create(): View
    {
        $kategoriList = KategoriMateri::where('is_aktif', true)->orderBy('urutan')->get();

        return view('guru.manajemen_materi.create', compact('kategoriList'));
    }

    /**
     * Menyimpan materi baru beserta butir latihan soal ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'tingkat_kelas' => 'required|string|max:50',
            'deskripsi_singkat' => 'nullable|string',
            'penjelasan' => 'required|string',
            'contoh_teks' => 'nullable|string',
            'xp_reward' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,draft',
            'gambar_materi' => 'nullable|image|max:3072',
            'audio_materi' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac|max:15360',
        ], [
            'judul.required' => 'Judul materi wajib diisi!',
            'kategori.required' => 'Kategori materi wajib dipilih!',
            'tingkat_kelas.required' => 'Tingkat kelas wajib ditentukan!',
            'penjelasan.required' => 'Penjelasan teori materi wajib diisi!',
            'gambar_materi.image' => 'File cover harus berupa gambar (jpg, png, webp)!',
            'audio_materi.mimes' => 'File audio harus berformat MP3, WAV, atau M4A!',
        ]);

        $data = $request->only([
            'judul',
            'kategori',
            'tingkat_kelas',
            'deskripsi_singkat',
            'penjelasan',
            'contoh_teks',
            'xp_reward',
            'status',
        ]);

        $data['xp_reward'] = $data['xp_reward'] ?? 50;

        // Upload Gambar Materi jika ada
        if ($request->hasFile('gambar_materi')) {
            $data['gambar_materi'] = $request->file('gambar_materi')->store('materi/images', 'public');
        }

        // Upload Audio Materi jika ada
        if ($request->hasFile('audio_materi')) {
            $data['audio_materi'] = $request->file('audio_materi')->store('materi/audio', 'public');
        }

        $materi = Materi::create($data);

        // Simpan Latihan Soal Pemahaman jika ada diinput
        $this->syncSoals($request, $materi);

        return redirect()->route('guru.materi.index')->with('success', 'Modul materi baru berhasil disimpan dan dipublikasikan!');
    }

    /**
     * Menampilkan detail preview materi (tampilan yang dilihat siswa).
     */
    public function show(Materi $materi): View
    {
        $materi->load('soals');

        return view('guru.manajemen_materi.show', compact('materi'));
    }

    /**
     * Menampilkan formulir edit materi.
     */
    public function edit(Materi $materi): View
    {
        $materi->load('soals');
        $kategoriList = KategoriMateri::where('is_aktif', true)->orderBy('urutan')->get();

        return view('guru.manajemen_materi.edit', compact('materi', 'kategoriList'));
    }

    /**
     * Memperbarui data materi dan butir soal latihannya.
     */
    public function update(Request $request, Materi $materi): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'tingkat_kelas' => 'required|string|max:50',
            'deskripsi_singkat' => 'nullable|string',
            'penjelasan' => 'required|string',
            'contoh_teks' => 'nullable|string',
            'xp_reward' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,draft',
            'gambar_materi' => 'nullable|image|max:3072',
            'audio_materi' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac|max:15360',
        ]);

        $data = $request->only([
            'judul',
            'kategori',
            'tingkat_kelas',
            'deskripsi_singkat',
            'penjelasan',
            'contoh_teks',
            'xp_reward',
            'status',
        ]);

        // Ganti gambar jika diunggah baru
        if ($request->hasFile('gambar_materi')) {
            if ($materi->gambar_materi && Storage::disk('public')->exists($materi->gambar_materi)) {
                Storage::disk('public')->delete($materi->gambar_materi);
            }
            $data['gambar_materi'] = $request->file('gambar_materi')->store('materi/images', 'public');
        }

        // Ganti audio jika diunggah baru
        if ($request->hasFile('audio_materi')) {
            if ($materi->audio_materi && Storage::disk('public')->exists($materi->audio_materi)) {
                Storage::disk('public')->delete($materi->audio_materi);
            }
            $data['audio_materi'] = $request->file('audio_materi')->store('materi/audio', 'public');
        }

        $materi->update($data);

        // Hapus soal lama dan buat ulang dari form edit
        $this->syncSoals($request, $materi, true);

        return redirect()->route('guru.materi.index')->with('success', 'Perubahan modul materi berhasil disimpan!');
    }

    /**
     * Menghapus materi beserta seluruh latihan soal dan file medianya.
     */
    public function destroy(Materi $materi): RedirectResponse
    {
        // Hapus file gambar
        if ($materi->gambar_materi && Storage::disk('public')->exists($materi->gambar_materi)) {
            Storage::disk('public')->delete($materi->gambar_materi);
        }

        // Hapus file audio
        if ($materi->audio_materi && Storage::disk('public')->exists($materi->audio_materi)) {
            Storage::disk('public')->delete($materi->audio_materi);
        }

        // Hapus audio pada soal jika ada
        foreach ($materi->soals as $soal) {
            if ($soal->audio_soal && Storage::disk('public')->exists($soal->audio_soal)) {
                Storage::disk('public')->delete($soal->audio_soal);
            }
        }

        $materi->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Modul materi berhasil dihapus.');
    }

    /**
     * Helper untuk memproses penyimpanan butir soal latihan pemahaman.
     */
    private function syncSoals(Request $request, Materi $materi, bool $isUpdate = false): void
    {
        if ($isUpdate) {
            // Hapus file audio soal lama sebelum hapus record
            foreach ($materi->soals as $soalLama) {
                if ($soalLama->audio_soal && Storage::disk('public')->exists($soalLama->audio_soal)) {
                    Storage::disk('public')->delete($soalLama->audio_soal);
                }
            }
            $materi->soals()->delete();
        }

        $soalsInput = $request->input('soals', []);

        if (is_array($soalsInput)) {
            foreach ($soalsInput as $index => $item) {
                // Abaikan jika pertanyaan kosong
                if (empty($item['pertanyaan'])) {
                    continue;
                }

                $audioSoalPath = null;
                // Cek upload audio soal listening
                if ($request->hasFile("soals.{$index}.audio_soal")) {
                    $audioSoalPath = $request->file("soals.{$index}.audio_soal")->store('materi/soal_audio', 'public');
                }

                MateriSoal::create([
                    'materi_id' => $materi->id,
                    'tipe_soal' => $item['tipe_soal'] ?? 'pilgan',
                    'pertanyaan' => $item['pertanyaan'],
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
    }
}
