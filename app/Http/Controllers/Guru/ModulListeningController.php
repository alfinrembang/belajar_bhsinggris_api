<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\StoreModulListeningRequest;
use App\Http\Requests\Guru\UpdateModulListeningRequest;
use App\Models\ListeningAudio;
use App\Models\ListeningSoal;
use App\Models\ModulListening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ModulListeningController extends Controller
{
    /**
     * Menampilkan daftar semua track rekaman modul listening guru.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $query = ModulListening::withCount('soals')->latest();

        // Isolasi Modul Listening: Guru hanya melihat miliknya sendiri atau modul umum
        if ($user && $user->role === 'guru') {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            });
        }

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

        // Statistik Ringkas Berdasarkan Hak Akses Guru
        $totalAudioQuery = ModulListening::query();
        $totalSoalQuery = ListeningSoal::query();
        $totalAktifQuery = ModulListening::where('status', 'aktif');
        if ($user && $user->role === 'guru') {
            $totalAudioQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            });
            $totalSoalQuery->whereHas('modulListening', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            });
            $totalAktifQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            });
        }
        $totalAudio = $totalAudioQuery->count();
        $totalSoal = $totalSoalQuery->count();
        $totalAktif = $totalAktifQuery->count();

        return view('guru.modul_listening.index', compact('listenings', 'totalAudio', 'totalSoal', 'totalAktif'));
    }

    /**
     * Menampilkan formulir buat modul audio listening baru.
     */
    public function create(): View
    {
        $kelasDiampu = auth()->user()->kelas_diampu ?? [];

        return view('guru.modul_listening.create', compact('kelasDiampu'));
    }

    /**
     * Menyimpan modul listening baru beserta daftar audio track dan butir soal.
     */
    public function store(StoreModulListeningRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $data = $request->only([
            'judul',
            'deskripsi',
            'tingkat_kesulitan',
            'tingkat_kelas',
            'target_rombel',
            'xp_reward',
            'status',
        ]);

        $data['user_id'] = auth()->id();
        $data['xp_reward'] = $data['xp_reward'] ?? 50;

        // Bersihkan array tujuan belajar
        if ($request->has('tujuan_belajar')) {
            $data['tujuan_belajar'] = array_values(array_filter($request->tujuan_belajar, fn ($item) => ! empty(trim($item))));
        }

        $modul = ModulListening::create($data);

        // 1. Simpan daftar audio tracks
        $createdAudios = []; // trackNomor => ListeningAudio
        $firstAudio = null;

        if ($request->has('audios') && is_array($request->audios)) {
            $trackNo = 1;
            foreach ($request->audios as $audioData) {
                if (! empty($audioData['is_deleted']) && $audioData['is_deleted'] == '1') {
                    continue;
                }

                $filePath = null;
                if (isset($audioData['audio_file']) && $audioData['audio_file'] instanceof UploadedFile) {
                    $filePath = $audioData['audio_file']->store('listening/audio', 'public');
                } elseif (! empty($audioData['audio_url_input'])) {
                    $filePath = $audioData['audio_url_input'];
                }

                if ($filePath) {
                    $audioTitle = ! empty($audioData['judul_audio']) ? $audioData['judul_audio'] : "Audio Track #{$trackNo}";
                    $audioRecord = ListeningAudio::create([
                        'modul_listening_id' => $modul->id,
                        'nomor_track' => $trackNo,
                        'judul_audio' => $audioTitle,
                        'audio_file' => $filePath,
                        'durasi' => $audioData['durasi'] ?? null,
                    ]);

                    $createdAudios[$trackNo] = $audioRecord;
                    if (! $firstAudio) {
                        $firstAudio = $audioRecord;
                    }
                    $trackNo++;
                }
            }
        } elseif ($request->hasFile('audio_file') || $request->filled('audio_url_input')) {
            // Fallback audio tunggal
            $filePath = $request->hasFile('audio_file')
                ? $request->file('audio_file')->store('listening/audio', 'public')
                : $request->audio_url_input;

            $audioRecord = ListeningAudio::create([
                'modul_listening_id' => $modul->id,
                'nomor_track' => 1,
                'judul_audio' => $request->audio_title ?: 'Audio Utama',
                'audio_file' => $filePath,
                'durasi' => $request->durasi,
            ]);
            $createdAudios[1] = $audioRecord;
            $firstAudio = $audioRecord;
        }

        // Set audio utama di modul_listenings untuk kompatibilitas
        if ($firstAudio) {
            $modul->update([
                'audio_file' => $firstAudio->audio_file,
                'audio_title' => $firstAudio->judul_audio,
                'durasi' => $firstAudio->durasi,
            ]);
        }

        // 2. Simpan butir-butir soal latihan
        if ($request->has('soals') && is_array($request->soals)) {
            $nomor = 1;
            foreach ($request->soals as $soalData) {
                if (! empty($soalData['pertanyaan'])) {
                    $trackNomor = (int) ($soalData['audio_track_nomor'] ?? 1);
                    $targetAudio = $createdAudios[$trackNomor] ?? ($firstAudio ?? null);

                    ListeningSoal::create([
                        'modul_listening_id' => $modul->id,
                        'listening_audio_id' => $targetAudio?->id,
                        'nomor_soal' => $nomor++,
                        'pertanyaan' => $soalData['pertanyaan'],
                        'audio_file' => $targetAudio?->audio_file,
                        'audio_title' => $targetAudio?->judul_audio,
                        'durasi' => $targetAudio?->durasi,
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
        $user = auth()->user();
        if ($user && $user->role === 'guru' && $listening->user_id && $listening->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat modul listening guru lain.');
        }

        $listening->load(['audios', 'soals.listeningAudio']);

        return view('guru.modul_listening.show', compact('listening'));
    }

    /**
     * Menampilkan formulir edit modul listening.
     */
    public function edit(ModulListening $listening): View
    {
        $user = auth()->user();
        if ($user && $user->role === 'guru' && $listening->user_id && $listening->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit modul listening guru lain.');
        }

        $listening->load(['audios', 'soals.listeningAudio']);
        $kelasDiampu = auth()->user()->kelas_diampu ?? [];

        return view('guru.modul_listening.edit', compact('listening', 'kelasDiampu'));
    }

    /**
     * Memperbarui data modul listening beserta daftar audio track dan butir soal latihannya.
     */
    public function update(UpdateModulListeningRequest $request, ModulListening $listening): RedirectResponse
    {
        $validated = $request->validated();

        $data = $request->only([
            'judul',
            'deskripsi',
            'tingkat_kesulitan',
            'tingkat_kelas',
            'target_rombel',
            'xp_reward',
            'status',
        ]);

        if ($request->has('tujuan_belajar')) {
            $data['tujuan_belajar'] = array_values(array_filter($request->tujuan_belajar, fn ($item) => ! empty(trim($item))));
        }

        // Sinkronisasi Audio Tracks
        $oldAudioFiles = $listening->audios()->whereNotNull('audio_file')->pluck('audio_file')->toArray();
        $retainedAudioFiles = [];
        $createdAudios = []; // trackNomor => ListeningAudio
        $firstAudio = null;

        $existingAudiosMap = $listening->audios->keyBy('id');

        if ($request->has('audios') && is_array($request->audios)) {
            $trackNo = 1;
            $processedAudioIds = [];

            foreach ($request->audios as $audioData) {
                if (! empty($audioData['is_deleted']) && $audioData['is_deleted'] == '1') {
                    continue;
                }

                $existingId = $audioData['id'] ?? null;
                $audioRecord = $existingId ? ($existingAudiosMap[$existingId] ?? null) : null;
                $filePath = $audioRecord?->audio_file;

                // Cek unggahan file baru atau url
                if (isset($audioData['audio_file']) && $audioData['audio_file'] instanceof UploadedFile) {
                    $filePath = $audioData['audio_file']->store('listening/audio', 'public');
                } elseif (! empty($audioData['audio_url_input'])) {
                    $filePath = $audioData['audio_url_input'];
                } elseif (! empty($audioData['existing_audio_file'])) {
                    $filePath = $audioData['existing_audio_file'];
                }

                if ($filePath) {
                    $audioTitle = ! empty($audioData['judul_audio']) ? $audioData['judul_audio'] : "Audio Track #{$trackNo}";
                    $durasi = $audioData['durasi'] ?? $audioRecord?->durasi;

                    if ($audioRecord) {
                        $audioRecord->update([
                            'nomor_track' => $trackNo,
                            'judul_audio' => $audioTitle,
                            'audio_file' => $filePath,
                            'durasi' => $durasi,
                        ]);
                        $processedAudioIds[] = $audioRecord->id;
                    } else {
                        $audioRecord = ListeningAudio::create([
                            'modul_listening_id' => $listening->id,
                            'nomor_track' => $trackNo,
                            'judul_audio' => $audioTitle,
                            'audio_file' => $filePath,
                            'durasi' => $durasi,
                        ]);
                        $processedAudioIds[] = $audioRecord->id;
                    }

                    $retainedAudioFiles[] = $filePath;
                    $createdAudios[$trackNo] = $audioRecord;
                    if (! $firstAudio) {
                        $firstAudio = $audioRecord;
                    }
                    $trackNo++;
                }
            }

            // Hapus track audio yang dihapus oleh guru
            $listening->audios()->whereNotIn('id', $processedAudioIds)->delete();
        }

        // Simpan info audio utama di tabel modul_listenings
        if ($firstAudio) {
            $data['audio_file'] = $firstAudio->audio_file;
            $data['audio_title'] = $firstAudio->judul_audio;
            $data['durasi'] = $firstAudio->durasi;
        }

        $listening->update($data);

        // Sinkronisasi butir soal
        if ($request->has('soals') && is_array($request->soals)) {
            $listening->soals()->delete();
            $nomor = 1;

            foreach ($request->soals as $soalData) {
                if (! empty($soalData['pertanyaan'])) {
                    $trackNomor = (int) ($soalData['audio_track_nomor'] ?? 1);
                    $targetAudio = $createdAudios[$trackNomor] ?? ($firstAudio ?? null);

                    ListeningSoal::create([
                        'modul_listening_id' => $listening->id,
                        'listening_audio_id' => $targetAudio?->id,
                        'nomor_soal' => $nomor++,
                        'pertanyaan' => $soalData['pertanyaan'],
                        'audio_file' => $targetAudio?->audio_file,
                        'audio_title' => $targetAudio?->judul_audio,
                        'durasi' => $targetAudio?->durasi,
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

        // Hapus file fisik audio lama yang dihapus/diganti
        foreach ($oldAudioFiles as $oldFile) {
            if (! in_array($oldFile, $retainedAudioFiles) && ! str_starts_with($oldFile, 'http') && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }
        }

        return redirect()->route('guru.listening.index')->with('success', "Perubahan modul listening '{$listening->judul}' berhasil disimpan!");
    }

    /**
     * Menghapus modul listening beserta file audio dan butir soalnya.
     */
    public function destroy(ModulListening $listening): RedirectResponse
    {
        $user = auth()->user();
        if ($user && $user->role === 'guru' && $listening->user_id && $listening->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus modul listening guru lain.');
        }

        $judul = $listening->judul;

        if ($listening->audio_file && Storage::disk('public')->exists($listening->audio_file)) {
            Storage::disk('public')->delete($listening->audio_file);
        }

        // Hapus file fisik semua track audio modul
        foreach ($listening->audios as $audio) {
            if ($audio->audio_file && ! str_starts_with($audio->audio_file, 'http') && Storage::disk('public')->exists($audio->audio_file)) {
                Storage::disk('public')->delete($audio->audio_file);
            }
        }

        $listening->delete();

        return redirect()->route('guru.listening.index')->with('success', "Modul listening '{$judul}' berhasil dihapus.");
    }
}
