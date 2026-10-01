<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ListeningSoal;
use App\Models\ModulListening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ModulListeningController extends Controller
{
    /**
     * Menampilkan daftar semua track rekaman modul listening guru.
     */
    public function index(Request $request): View
    {
        $query = ModulListening::withCount('soals')->latest();

        // Filter Tingkat Kesulitan (Beginner, Intermediate, Advanced)
        if ($request->filled('tingkat')) {
            $query->where('tingkat_kesulitan', $request->tingkat);
        }

        // Filter Tingkat Kelas
        if ($request->filled('kelas')) {
            $query->where(function ($q) use ($request) {
                $q->where('tingkat_kelas', $request->kelas)
                    ->orWhere('tingkat_kelas', 'Semua Kelas');
            });
        }

        // Filter Pencarian Judul / Transkrip / Deskripsi
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('transkrip', 'like', "%{$search}%");
            });
        }

        $listenings = $query->paginate(10)->withQueryString();

        // Statistik Ringkas
        $totalAudio = ModulListening::count();
        $totalSoal = ListeningSoal::count();
        $totalAktif = ModulListening::where('status', 'aktif')->count();

        return view('guru.modul_listening.index', compact('listenings', 'totalAudio', 'totalSoal', 'totalAktif'));
    }

    /**
     * Menampilkan formulir buat modul audio listening baru.
     */
    public function create(): View
    {
        return view('guru.modul_listening.create');
    }

    /**
     * Menyimpan modul listening baru beserta file audio dan butir soal.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tingkat_kesulitan' => 'required|in:Beginner,Intermediate,Advanced',
            'tingkat_kelas' => 'required|string|max:50',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac|max:25600',
            'audio_url_input' => 'nullable|url|max:500',
            'audio_title' => 'nullable|string|max:100',
            'durasi' => 'nullable|string|max:20',
            'transkrip' => 'nullable|string',
            'tujuan_belajar' => 'nullable|array',
            'tujuan_belajar.*' => 'nullable|string',
            'xp_reward' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,draft',
            'soals' => 'nullable|array',
            'soals.*.pertanyaan' => 'required_with:soals|string',
            'soals.*.pilihan_a' => 'required_with:soals|string',
            'soals.*.pilihan_b' => 'required_with:soals|string',
            'soals.*.pilihan_c' => 'required_with:soals|string',
            'soals.*.pilihan_d' => 'required_with:soals|string',
            'soals.*.jawaban_benar' => 'required_with:soals|in:A,B,C,D',
            'soals.*.pembahasan' => 'nullable|string',
        ], [
            'judul.required' => 'Judul percakapan listening wajib diisi!',
            'tingkat_kesulitan.required' => 'Silakan pilih tingkat kesulitan!',
            'audio_file.mimes' => 'File audio harus berformat MP3, WAV, atau M4A!',
        ]);

        $data = $request->only([
            'judul',
            'deskripsi',
            'tingkat_kesulitan',
            'tingkat_kelas',
            'audio_title',
            'durasi',
            'transkrip',
            'xp_reward',
            'status',
        ]);

        $data['xp_reward'] = $data['xp_reward'] ?? 50;

        // Bersihkan array tujuan belajar
        if ($request->has('tujuan_belajar')) {
            $data['tujuan_belajar'] = array_values(array_filter($request->tujuan_belajar, fn ($item) => ! empty(trim($item))));
        }

        // Upload file audio
        if ($request->hasFile('audio_file')) {
            $data['audio_file'] = $request->file('audio_file')->store('listening/audio', 'public');
        } elseif ($request->filled('audio_url_input')) {
            $data['audio_file'] = $request->audio_url_input;
        }

        $modul = ModulListening::create($data);

        // Simpan butir-butir soal latihan jika ada
        if ($request->has('soals') && is_array($request->soals)) {
            $nomor = 1;
            foreach ($request->soals as $soalData) {
                if (! empty($soalData['pertanyaan'])) {
                    ListeningSoal::create([
                        'modul_listening_id' => $modul->id,
                        'nomor_soal' => $nomor++,
                        'pertanyaan' => $soalData['pertanyaan'],
                        'pilihan_a' => $soalData['pilihan_a'] ?? '-',
                        'pilihan_b' => $soalData['pilihan_b'] ?? '-',
                        'pilihan_c' => $soalData['pilihan_c'] ?? '-',
                        'pilihan_d' => $soalData['pilihan_d'] ?? '-',
                        'jawaban_benar' => $soalData['jawaban_benar'] ?? 'A',
                        'pembahasan' => $soalData['pembahasan'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('guru.listening.index')->with('success', "Modul Listening '{$modul->judul}' berhasil ditambahkan!");
    }

    /**
     * Menampilkan detail modul listening dan pemutar audio serta daftar soal.
     */
    public function show(ModulListening $listening): View
    {
        $listening->load('soals');

        return view('guru.modul_listening.show', compact('listening'));
    }

    /**
     * Menampilkan formulir edit modul listening.
     */
    public function edit(ModulListening $listening): View
    {
        $listening->load('soals');

        return view('guru.modul_listening.edit', compact('listening'));
    }

    /**
     * Memperbarui data modul listening beserta audio dan butir soal latihannya.
     */
    public function update(Request $request, ModulListening $listening): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tingkat_kesulitan' => 'required|in:Beginner,Intermediate,Advanced',
            'tingkat_kelas' => 'required|string|max:50',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac|max:25600',
            'audio_url_input' => 'nullable|url|max:500',
            'audio_title' => 'nullable|string|max:100',
            'durasi' => 'nullable|string|max:20',
            'transkrip' => 'nullable|string',
            'tujuan_belajar' => 'nullable|array',
            'tujuan_belajar.*' => 'nullable|string',
            'xp_reward' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,draft',
            'soals' => 'nullable|array',
            'soals.*.pertanyaan' => 'required_with:soals|string',
            'soals.*.pilihan_a' => 'required_with:soals|string',
            'soals.*.pilihan_b' => 'required_with:soals|string',
            'soals.*.pilihan_c' => 'required_with:soals|string',
            'soals.*.pilihan_d' => 'required_with:soals|string',
            'soals.*.jawaban_benar' => 'required_with:soals|in:A,B,C,D',
            'soals.*.pembahasan' => 'nullable|string',
        ]);

        $data = $request->only([
            'judul',
            'deskripsi',
            'tingkat_kesulitan',
            'tingkat_kelas',
            'audio_title',
            'durasi',
            'transkrip',
            'xp_reward',
            'status',
        ]);

        // Bersihkan array tujuan belajar
        if ($request->has('tujuan_belajar')) {
            $data['tujuan_belajar'] = array_values(array_filter($request->tujuan_belajar, fn ($item) => ! empty(trim($item))));
        }

        // Ganti audio jika diunggah baru
        if ($request->hasFile('audio_file')) {
            if ($listening->audio_file && Storage::disk('public')->exists($listening->audio_file)) {
                Storage::disk('public')->delete($listening->audio_file);
            }
            $data['audio_file'] = $request->file('audio_file')->store('listening/audio', 'public');
        } elseif ($request->filled('audio_url_input')) {
            $data['audio_file'] = $request->audio_url_input;
        }

        $listening->update($data);

        // Sinkronisasi butir soal
        if ($request->has('soals') && is_array($request->soals)) {
            $listening->soals()->delete();
            $nomor = 1;
            foreach ($request->soals as $soalData) {
                if (! empty($soalData['pertanyaan'])) {
                    ListeningSoal::create([
                        'modul_listening_id' => $listening->id,
                        'nomor_soal' => $nomor++,
                        'pertanyaan' => $soalData['pertanyaan'],
                        'pilihan_a' => $soalData['pilihan_a'] ?? '-',
                        'pilihan_b' => $soalData['pilihan_b'] ?? '-',
                        'pilihan_c' => $soalData['pilihan_c'] ?? '-',
                        'pilihan_d' => $soalData['pilihan_d'] ?? '-',
                        'jawaban_benar' => $soalData['jawaban_benar'] ?? 'A',
                        'pembahasan' => $soalData['pembahasan'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('guru.listening.index')->with('success', "Perubahan modul listening '{$listening->judul}' berhasil disimpan!");
    }

    /**
     * Menghapus modul listening beserta file audio dan butir soalnya.
     */
    public function destroy(ModulListening $listening): RedirectResponse
    {
        $judul = $listening->judul;

        if ($listening->audio_file && Storage::disk('public')->exists($listening->audio_file)) {
            Storage::disk('public')->delete($listening->audio_file);
        }

        $listening->delete();

        return redirect()->route('guru.listening.index')->with('success', "Modul listening '{$judul}' berhasil dihapus.");
    }
}
