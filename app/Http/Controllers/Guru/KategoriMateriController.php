<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\StoreKategoriMateriRequest;
use App\Http\Requests\Guru\UpdateKategoriMateriRequest;
use App\Models\KategoriMateri;
use App\Models\Materi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KategoriMateriController extends Controller
{
    /**
     * Menampilkan daftar semua kategori materi pembelajaran.
     */
    public function index(Request $request): View
    {
        $query = KategoriMateri::withCount('materis')->orderBy('urutan', 'asc')->latest('id');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $kategoris = $query->paginate(12)->withQueryString();
        $totalKategori = KategoriMateri::count();

        return view('guru.manajemen_kategori.index', compact('kategoris', 'totalKategori'));
    }

    /**
     * Menampilkan formulir tambah kategori materi baru.
     */
    public function create(): View
    {
        $presetColors = [
            ['name' => 'Royal Blue', 'hex' => '#1E6BFF'],
            ['name' => 'Emerald Green', 'hex' => '#059669'],
            ['name' => 'Royal Violet', 'hex' => '#7C3AED'],
            ['name' => 'Warm Amber', 'hex' => '#D97706'],
            ['name' => 'Vibrant Pink', 'hex' => '#EC4899'],
            ['name' => 'Modern Teal', 'hex' => '#0D9488'],
            ['name' => 'Crimson Red', 'hex' => '#DC2626'],
            ['name' => 'Golden Yellow', 'hex' => '#EAB308'],
        ];

        $presetIcons = [
            ['name' => 'auto_stories', 'label' => 'Membaca / Reading', 'emoji' => '📚'],
            ['name' => 'spellcheck', 'label' => 'Tata Bahasa / Grammar', 'emoji' => '✍️'],
            ['name' => 'forum', 'label' => 'Percakapan / Speaking', 'emoji' => '🗣️'],
            ['name' => 'translate', 'label' => 'Kosakata / Vocabulary', 'emoji' => '📖'],
            ['name' => 'lightbulb', 'label' => 'Idioms & Tips', 'emoji' => '💡'],
            ['name' => 'headphones', 'label' => 'Listening & Audio', 'emoji' => '🎧'],
            ['name' => 'business_center', 'label' => 'Business English', 'emoji' => '💼'],
            ['name' => 'track_changes', 'label' => 'Latihan Soal / Ujian', 'emoji' => '🎯'],
        ];

        return view('guru.manajemen_kategori.create', compact('presetColors', 'presetIcons'));
    }

    /**
     * Menyimpan kategori materi baru ke database.
     */
    public function store(StoreKategoriMateriRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['nama']);
        $data['urutan'] = $data['urutan'] ?? (KategoriMateri::max('urutan') + 1);
        $data['is_aktif'] = $request->has('is_aktif') ? $request->boolean('is_aktif') : true;

        KategoriMateri::create($data);

        return redirect()->route('guru.kategori.index')->with('success', "Kategori '{$data['nama']}' berhasil ditambahkan!");
    }

    /**
     * Menampilkan detail kategori dan daftar modul materinya.
     */
    public function show(KategoriMateri $kategori): View
    {
        $kategori->load(['materis' => function ($q) {
            $q->withCount('soals')->latest();
        }]);

        return view('guru.manajemen_kategori.show', compact('kategori'));
    }

    /**
     * Menampilkan formulir edit kategori materi.
     */
    public function edit(KategoriMateri $kategori): View
    {
        $presetColors = [
            ['name' => 'Royal Blue', 'hex' => '#1E6BFF'],
            ['name' => 'Emerald Green', 'hex' => '#059669'],
            ['name' => 'Royal Violet', 'hex' => '#7C3AED'],
            ['name' => 'Warm Amber', 'hex' => '#D97706'],
            ['name' => 'Vibrant Pink', 'hex' => '#EC4899'],
            ['name' => 'Modern Teal', 'hex' => '#0D9488'],
            ['name' => 'Crimson Red', 'hex' => '#DC2626'],
            ['name' => 'Golden Yellow', 'hex' => '#EAB308'],
        ];

        $presetIcons = [
            ['name' => 'auto_stories', 'label' => 'Membaca / Reading', 'emoji' => '📚'],
            ['name' => 'spellcheck', 'label' => 'Tata Bahasa / Grammar', 'emoji' => '✍️'],
            ['name' => 'forum', 'label' => 'Percakapan / Speaking', 'emoji' => '🗣️'],
            ['name' => 'translate', 'label' => 'Kosakata / Vocabulary', 'emoji' => '📖'],
            ['name' => 'lightbulb', 'label' => 'Idioms & Tips', 'emoji' => '💡'],
            ['name' => 'headphones', 'label' => 'Listening & Audio', 'emoji' => '🎧'],
            ['name' => 'business_center', 'label' => 'Business English', 'emoji' => '💼'],
            ['name' => 'track_changes', 'label' => 'Latihan Soal / Ujian', 'emoji' => '🎯'],
        ];

        return view('guru.manajemen_kategori.edit', compact('kategori', 'presetColors', 'presetIcons'));
    }

    /**
     * Memperbarui data kategori materi.
     */
    public function update(UpdateKategoriMateriRequest $request, KategoriMateri $kategori): RedirectResponse
    {
        $namaLama = $kategori->nama;
        $data = $request->validated();

        $data['slug'] = Str::slug($data['nama']);
        $data['urutan'] = $data['urutan'] ?? $kategori->urutan;
        $data['is_aktif'] = $request->boolean('is_aktif');

        $kategori->update($data);

        // Jika nama kategori berubah, perbarui juga nilai kategori pada materi terkait
        if ($namaLama !== $data['nama']) {
            Materi::where('kategori', $namaLama)->update(['kategori' => $data['nama']]);
        }

        return redirect()->route('guru.kategori.index')->with('success', "Perubahan kategori '{$data['nama']}' berhasil disimpan!");
    }

    /**
     * Menghapus kategori materi dari sistem.
     */
    public function destroy(KategoriMateri $kategori): RedirectResponse
    {
        $jumlahMateri = $kategori->materis()->count();

        if ($jumlahMateri > 0) {
            return redirect()->route('guru.kategori.index')->with('error', "Kategori '{$kategori->nama}' tidak dapat dihapus karena masih digunakan oleh {$jumlahMateri} modul materi!");
        }

        $nama = $kategori->nama;
        $kategori->delete();

        return redirect()->route('guru.kategori.index')->with('success', "Kategori '{$nama}' berhasil dihapus.");
    }
}
