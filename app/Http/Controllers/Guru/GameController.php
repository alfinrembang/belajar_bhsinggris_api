<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\FishingSoal;
use App\Models\Siswa;
use App\Models\SiswaFishingProgres;
use App\Models\WerewolfSoal;
use App\Services\GameExcelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GameController extends Controller
{
    /**
     * Menampilkan dashboard utama Mini Games & Leaderboard untuk Guru.
     * Mengintegrasikan data dinamis dari Tangkap Ikan (Word Fishing) & Tebak Serigala (Wolf vs Sheep).
     */
    public function index(Request $request): View
    {
        $search = trim($request->query('q', ''));
        $levelFilter = $request->query('level', '');
        $diffFilter = $request->query('difficulty', '');
        $werewolfThemeFilter = $request->query('werewolf_theme', '');
        $rombelFilter = $request->query('rombel', '');

        // Master Rombel & Tingkat Kelas untuk filter
        $daftarRombelGrouped = Siswa::getDaftarRombelGrouped();
        $daftarKelas = Siswa::getDaftarKelas();

        // 1. Ambil Skor Memancing per Siswa
        $fishingScores = SiswaFishingProgres::selectRaw('siswa_id, SUM(high_score) as total_fishing_score, COUNT(id) as total_fish_caught')
            ->whereNotNull('siswa_id')
            ->groupBy('siswa_id')
            ->get()
            ->keyBy('siswa_id');

        // 2. Ambil Data Siswa (Dapat difilter berdasarkan rombel/kelas) & Hitung Leaderboard Aktual
        $siswaQuery = Siswa::latest();
        if (!empty($rombelFilter)) {
            if (str_starts_with($rombelFilter, 'tingkat:')) {
                $tingkat = substr($rombelFilter, 8);
                $siswaQuery->where('kelas', $tingkat);
            } else {
                $parts = explode(' ', $rombelFilter);
                if (count($parts) >= 3) {
                    $siswaQuery->where('kelas', $parts[0])
                               ->where('jurusan', $parts[1])
                               ->where('no_kelas', $parts[2]);
                } else {
                    $siswaQuery->where(function($q) use ($rombelFilter) {
                        $q->where('kelas', $rombelFilter)
                          ->orWhere('jurusan', $rombelFilter);
                    });
                }
            }
        }
        $siswas = $siswaQuery->get();
        $leaderboard = [];

        foreach ($siswas as $s) {
            $fScore = isset($fishingScores[$s->id]) ? (int) $fishingScores[$s->id]->total_fishing_score : 0;
            $fCount = isset($fishingScores[$s->id]) ? (int) $fishingScores[$s->id]->total_fish_caught : 0;
            $koin = (int) ($s->koin ?? 120);

            // Perhitungan Akumulasi Total Poin Game
            $totalPoin = $fScore + ($koin * 5);

            $kelasLengkap = $s->kelas;
            if (! empty($s->jurusan)) {
                $kelasLengkap .= ' '.$s->jurusan;
            }
            if (! empty($s->no_kelas)) {
                $kelasLengkap .= ' '.$s->no_kelas;
            }

            $leaderboard[] = [
                'id' => $s->id,
                'nama' => $s->nama_lengkap,
                'kelas' => $kelasLengkap ?: 'XII RPL 1',
                'no_absen' => (string) ($s->no_absen ?? '-'),
                'total_skor' => $totalPoin,
                'skor_memancing' => $fScore,
                'ikan_tertangkap' => $fCount,
                'koin' => $koin,
            ];
        }

        // Urutkan dari total_skor tertinggi
        usort($leaderboard, function ($a, $b) {
            return $b['total_skor'] <=> $a['total_skor'];
        });

        // Fallback jika belum ada data riwayat game
        if (count($leaderboard) < 3) {
            $defaultList = [
                [
                    'id' => 101,
                    'nama' => 'Budi Pratama',
                    'kelas' => 'XII RPL 1',
                    'no_absen' => '14',
                    'total_skor' => 2450,
                    'skor_memancing' => 1250,
                    'ikan_tertangkap' => 8,
                    'koin' => 240,
                ],
                [
                    'id' => 102,
                    'nama' => 'Siti Rahma',
                    'kelas' => 'XII RPL 1',
                    'no_absen' => '28',
                    'total_skor' => 2100,
                    'skor_memancing' => 1050,
                    'ikan_tertangkap' => 7,
                    'koin' => 210,
                ],
                [
                    'id' => 103,
                    'nama' => 'Ahmad Rizki',
                    'kelas' => 'XII RPL 2',
                    'no_absen' => '05',
                    'total_skor' => 1890,
                    'skor_memancing' => 950,
                    'ikan_tertangkap' => 6,
                    'koin' => 180,
                ],
            ];

            foreach ($defaultList as $def) {
                $exists = false;
                foreach ($leaderboard as $l) {
                    if ($l['nama'] === $def['nama']) {
                        $exists = true;
                        break;
                    }
                }
                if (! $exists) {
                    $leaderboard[] = $def;
                }
            }

            usort($leaderboard, function ($a, $b) {
                return $b['total_skor'] <=> $a['total_skor'];
            });
        }

        // Filter pencarian leaderboard jika ada input search
        if (! empty($search)) {
            $leaderboard = array_filter($leaderboard, function ($item) use ($search) {
                return stripos($item['nama'], $search) !== false ||
                       stripos($item['kelas'], $search) !== false;
            });
        }

        // Hitung Rank
        $rankedLeaderboard = [];
        $rankCounter = 1;
        foreach ($leaderboard as $item) {
            $item['rank'] = $rankCounter++;
            $rankedLeaderboard[] = $item;
        }

        // 3. Statistik Ringkasan Atas
        $topPlayer = $rankedLeaderboard[0] ?? null;
        $totalPesertaAktif = count($siswas);
        $totalSoalIkan = FishingSoal::count();
        $totalSoalWerewolf = WerewolfSoal::count();

        // 4. Query Bank Soal Tangkap Ikan
        $soalQuery = FishingSoal::query()->latest();
        if (! empty($search)) {
            $soalQuery->where(function ($q) use ($search) {
                $q->where('word', 'like', "%{$search}%")
                    ->orWhere('meaning', 'like', "%{$search}%")
                    ->orWhere('part_of_speech', 'like', "%{$search}%");
            });
        }
        if (! empty($levelFilter)) {
            $soalQuery->where('level', (int) $levelFilter);
        }
        if (! empty($diffFilter)) {
            $soalQuery->where('difficulty', $diffFilter);
        }
        $daftarSoalIkan = $soalQuery->paginate(10, ['*'], 'fishing_page')->withQueryString();

        // 5. Query Bank Soal Tebak Serigala (Wolf vs Sheep)
        $werewolfQuery = WerewolfSoal::query()->latest();
        if (! empty($search)) {
            $werewolfQuery->where(function ($q) use ($search) {
                $q->where('domba_word', 'like', "%{$search}%")
                    ->orWhere('domba_meaning', 'like', "%{$search}%")
                    ->orWhere('serigala_word', 'like', "%{$search}%")
                    ->orWhere('serigala_meaning', 'like', "%{$search}%")
                    ->orWhere('theme', 'like', "%{$search}%");
            });
        }
        if (! empty($werewolfThemeFilter)) {
            $werewolfQuery->where('theme', $werewolfThemeFilter);
        }
        $daftarSoalWerewolf = $werewolfQuery->paginate(10, ['*'], 'werewolf_page')->withQueryString();
        $werewolfThemes = WerewolfSoal::distinct()->pluck('theme')->filter()->values();

        return view('guru.mini_game_leaderboard.index', compact(
            'rankedLeaderboard',
            'topPlayer',
            'totalPesertaAktif',
            'totalSoalIkan',
            'totalSoalWerewolf',
            'daftarSoalIkan',
            'daftarSoalWerewolf',
            'werewolfThemes',
            'werewolfThemeFilter',
            'search',
            'levelFilter',
            'diffFilter',
            'rombelFilter',
            'daftarRombelGrouped',
            'daftarKelas'
        ));
    }

    // ==========================================
    // 1. KONTROL BANK SOAL WORD FISHING
    // ==========================================

    public function storeFishingSoal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'word' => ['required', 'string', 'max:100'],
            'part_of_speech' => ['required', 'string', 'max:50'],
            'meaning' => ['required', 'string', 'max:255'],
            'level' => ['required', 'integer', 'min:1', 'max:9'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'correct_answer' => ['required', 'string', 'max:255'],
            'option_2' => ['required', 'string', 'max:255'],
            'option_3' => ['required', 'string', 'max:255'],
            'option_4' => ['required', 'string', 'max:255'],
            'explanation' => ['nullable', 'string'],
            'example_sentence' => ['nullable', 'string'],
        ], [
            'word.required' => 'Kosakata bahasa Inggris wajib diisi.',
            'part_of_speech.required' => 'Jenis kata (Part of Speech) wajib dipilih.',
            'meaning.required' => 'Arti kata dalam bahasa Indonesia wajib diisi.',
            'correct_answer.required' => 'Jawaban benar wajib diisi.',
            'option_2.required' => 'Pilihan jawaban pengecoh 1 wajib diisi.',
            'option_3.required' => 'Pilihan jawaban pengecoh 2 wajib diisi.',
            'option_4.required' => 'Pilihan jawaban pengecoh 3 wajib diisi.',
        ]);

        $word = strtoupper(trim($validated['word']));

        // Cek Anti-Duplikat
        if (FishingSoal::whereRaw('UPPER(TRIM(word)) = ?', [$word])->exists()) {
            return redirect()->route('guru.game.index')
                ->withErrors(['word' => "Kosakata '{$word}' sudah ada di database. Silakan gunakan kosakata lain untuk menghindari duplikasi!"]);
        }

        $options = [
            $validated['correct_answer'],
            $validated['option_2'],
            $validated['option_3'],
            $validated['option_4'],
        ];

        FishingSoal::create([
            'word' => $word,
            'part_of_speech' => trim($validated['part_of_speech']),
            'meaning' => trim($validated['meaning']),
            'level' => (int) $validated['level'],
            'difficulty' => strtolower($validated['difficulty']),
            'options' => $options,
            'explanation' => $validated['explanation'] ? trim($validated['explanation']) : null,
            'example_sentence' => $validated['example_sentence'] ? trim($validated['example_sentence']) : null,
            'pull_energy' => 20,
        ]);

        return redirect()->route('guru.game.index')
            ->with('success', "Soal kosakata '{$word}' berhasil ditambahkan ke Game Tangkap Ikan!");
    }

    public function destroyFishingSoal($id): RedirectResponse
    {
        $soal = FishingSoal::findOrFail($id);
        $word = $soal->word;
        $soal->delete();

        return redirect()->route('guru.game.index')
            ->with('success', "Soal kosakata '{$word}' berhasil dihapus.");
    }

    /**
     * Import Soal Tangkap Ikan dari File Excel (.xlsx / .csv) dengan Deteksi Duplikat.
     */
    public function importFishingExcel(Request $request): RedirectResponse
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'max:5120'], // Max 5MB
        ], [
            'file_excel.required' => 'Silakan pilih file Excel (.xlsx atau .csv) untuk diunggah.',
        ]);

        $file = $request->file('file_excel');
        $ext = strtolower($file->getClientOriginalExtension());

        if (! in_array($ext, ['xlsx', 'csv', 'txt'])) {
            return redirect()->route('guru.game.index')
                ->withErrors(['file_excel' => 'Format file tidak didukung. Harap gunakan file dengan ekstensi .xlsx atau .csv']);
        }

        $result = GameExcelService::importFishing($file->getRealPath(), $ext);

        $msg = "Import Berhasil! {$result['imported']} soal baru ditambahkan.";
        if ($result['skipped'] > 0) {
            $msg .= " Sebanyak {$result['skipped']} data dilewati karena sudah ada di database (mencegah duplikat).";
        }

        return redirect()->route('guru.game.index')->with('success', $msg);
    }

    /**
     * Mengunduh Template File Excel/CSV untuk Game Tangkap Ikan.
     */
    public function downloadFishingTemplate(): StreamedResponse
    {
        $fileName = 'Template_Soal_Tangkap_Ikan.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            // Tulis BOM UTF-8 agar Excel membuka karakter dengan rapi
            fwrite($handle, "\xEF\xBB\xBF");

            // Header kolom
            fputcsv($handle, [
                'Level',
                'Difficulty',
                'Word',
                'Part of Speech',
                'Arti / Jawaban Benar',
                'Pilihan Pengecoh 1',
                'Pilihan Pengecoh 2',
                'Pilihan Pengecoh 3',
                'Penjelasan Edukasi',
                'Contoh Kalimat',
            ]);

            // Baris Contoh
            fputcsv($handle, [
                '1',
                'mudah',
                'DOLPHIN',
                'Noun (Kata Benda)',
                'Lumba-Lumba',
                'Hiu Putih',
                'Ikan Paus',
                'Penyu Laut',
                'Dolphin adalah mamalia laut yang cerdas dan bersahabat.',
                'The dolphin jumped gracefully through the blue ocean waves.',
            ]);
            fputcsv($handle, [
                '2',
                'sedang',
                'GENEROUS',
                'Adjective (Kata Sifat)',
                'Dermawan / Suka Berbagi',
                'Pelit',
                'Keras Kepala',
                'Pemarah',
                'Generous adalah sifat seseorang yang suka memberi dan berbagi.',
                'He was generous enough to share his lunch with his hungry friend.',
            ]);

            fclose($handle);
        }, 200, $headers);
    }

    // ==========================================
    // 2. KONTROL BANK SOAL TEBAK SERIGALA (WOLF VS SHEEP)
    // ==========================================

    public function storeWerewolfSoal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'string', 'max:100'],
            'domba_word' => ['required', 'string', 'max:100'],
            'domba_meaning' => ['required', 'string', 'max:255'],
            'domba_clue_1' => ['required', 'string', 'max:255'],
            'domba_clue_2' => ['required', 'string', 'max:255'],
            'serigala_word' => ['required', 'string', 'max:100'],
            'serigala_meaning' => ['required', 'string', 'max:255'],
            'serigala_clue_1' => ['required', 'string', 'max:255'],
            'serigala_clue_2' => ['required', 'string', 'max:255'],
            'english_difference' => ['required', 'string'],
        ], [
            'theme.required' => 'Kategori / Tema wajib diisi.',
            'domba_word.required' => 'Kata Domba (Kata A) wajib diisi.',
            'domba_meaning.required' => 'Arti Kata Domba wajib diisi.',
            'serigala_word.required' => 'Kata Serigala (Kata B) wajib diisi.',
            'serigala_meaning.required' => 'Arti Kata Serigala wajib diisi.',
            'english_difference.required' => 'Penjelasan perbedaan bahasa Inggris wajib diisi.',
        ]);

        $dombaWord = strtoupper(trim($validated['domba_word']));
        $serigalaWord = strtoupper(trim($validated['serigala_word']));

        // Cek Anti-Duplikat
        if (WerewolfSoal::whereRaw('UPPER(TRIM(domba_word)) = ? AND UPPER(TRIM(serigala_word)) = ?', [$dombaWord, $serigalaWord])->exists()) {
            return redirect()->route('guru.game.index')
                ->withErrors(['domba_word' => "Pasangan kata '{$dombaWord}' vs '{$serigalaWord}' sudah terdaftar. Data duplikat dicegah agar sistem tetap optimal!"]);
        }

        $dombaClues = [
            trim($validated['domba_clue_1']),
            trim($validated['domba_clue_2']),
        ];
        if ($request->filled('domba_clue_3')) {
            $dombaClues[] = trim($request->input('domba_clue_3'));
        }
        if ($request->filled('domba_clue_4')) {
            $dombaClues[] = trim($request->input('domba_clue_4'));
        }

        $serigalaClues = [
            trim($validated['serigala_clue_1']),
            trim($validated['serigala_clue_2']),
        ];
        if ($request->filled('serigala_clue_3')) {
            $serigalaClues[] = trim($request->input('serigala_clue_3'));
        }
        if ($request->filled('serigala_clue_4')) {
            $serigalaClues[] = trim($request->input('serigala_clue_4'));
        }

        WerewolfSoal::create([
            'theme' => trim($validated['theme']),
            'domba_word' => $dombaWord,
            'domba_meaning' => trim($validated['domba_meaning']),
            'domba_clues' => $dombaClues,
            'serigala_word' => $serigalaWord,
            'serigala_meaning' => trim($validated['serigala_meaning']),
            'serigala_clues' => $serigalaClues,
            'english_difference' => trim($validated['english_difference']),
            'is_active' => true,
        ]);

        return redirect()->route('guru.game.index')
            ->with('success', "Pasangan kata kartu '{$dombaWord}' vs '{$serigalaWord}' berhasil ditambahkan ke Tebak Serigala!");
    }

    public function destroyWerewolfSoal($id): RedirectResponse
    {
        $soal = WerewolfSoal::findOrFail($id);
        $pairName = "{$soal->domba_word} vs {$soal->serigala_word}";
        $soal->delete();

        return redirect()->route('guru.game.index')
            ->with('success', "Pasangan kata '{$pairName}' berhasil dihapus.");
    }

    /**
     * Import Pasangan Kata Tebak Serigala dari File Excel (.xlsx / .csv) dengan Deteksi Duplikat.
     */
    public function importWerewolfExcel(Request $request): RedirectResponse
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'max:5120'],
        ], [
            'file_excel.required' => 'Silakan pilih file Excel (.xlsx atau .csv) untuk diunggah.',
        ]);

        $file = $request->file('file_excel');
        $ext = strtolower($file->getClientOriginalExtension());

        if (! in_array($ext, ['xlsx', 'csv', 'txt'])) {
            return redirect()->route('guru.game.index')
                ->withErrors(['file_excel' => 'Format file tidak didukung. Harap gunakan file dengan ekstensi .xlsx atau .csv']);
        }

        $result = GameExcelService::importWerewolf($file->getRealPath(), $ext);

        $msg = "Import Berhasil! {$result['imported']} pasangan kata baru ditambahkan ke Tebak Serigala.";
        if ($result['skipped'] > 0) {
            $msg .= " Sebanyak {$result['skipped']} pasangan kata dilewati karena sudah ada di database (bebas duplikat).";
        }

        return redirect()->route('guru.game.index')->with('success', $msg);
    }

    /**
     * Mengunduh Template File Excel/CSV untuk Game Tebak Serigala (Wolf vs Sheep).
     */
    public function downloadWerewolfTemplate(): StreamedResponse
    {
        $fileName = 'Template_Kata_Tebak_Serigala.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            // Header kolom
            fputcsv($handle, [
                'Kategori / Tema',
                'Kata Domba (Kata A)',
                'Arti Kata Domba',
                'Petunjuk Domba (Pisahkan dengan titik koma ;)',
                'Kata Serigala (Kata B)',
                'Arti Kata Serigala',
                'Petunjuk Serigala (Pisahkan dengan titik koma ;)',
                'Penjelasan Perbedaan Bahasa Inggris',
            ]);

            // Baris Contoh 1
            fputcsv($handle, [
                'Nature & Landscape',
                'FOREST',
                'Hutan Beriklim Sedang',
                'Has tall trees and shaded canopy; Home to bears and deer; Cooler weather than jungle; Covered with pine and oak trees',
                'JUNGLE',
                'Rimba Hutan Tropis',
                'Dense tangled tropical vegetation; High humidity and frequent rainfall; Inhabited by monkeys and leopards; Vines and wild undergrowth everywhere',
                'Forest refers to temperate woodlands with open floors, while Jungle is a dense, impenetrable tropical rainforest.',
            ]);

            // Baris Contoh 2
            fputcsv($handle, [
                'Animals',
                'WOLF',
                'Serigala',
                'Wild canine that hunts in organized packs; Howls at the moon at night; Has thick grey fur and piercing eyes; Fierce predator of the wilderness',
                'DOG',
                'Anjing Peliharaan',
                'Known as man best faithful friend; Barks cheerfully and wags its tail; Lives indoors with human family; Loves playing fetch with a ball',
                'Wolf is a wild pack-hunting carnivore, whereas Dog is a domesticated canine companion living with humans.',
            ]);

            fclose($handle);
        }, 200, $headers);
    }
}
