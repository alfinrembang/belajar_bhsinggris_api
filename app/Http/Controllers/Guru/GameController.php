<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\FishingSoal;
use App\Models\PronounceWord;
use App\Models\Siswa;
use App\Models\SiswaFishingProgres;
use App\Models\WerewolfSoal;
use App\Services\GameExcelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
        if (! empty($rombelFilter)) {
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
                    $siswaQuery->where(function ($q) use ($rombelFilter) {
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

        // 6. Query Bank Kosakata Adu Lafal (Pronounce Hero)
        $totalSoalPronounce = PronounceWord::count();
        $pronounceQuery = PronounceWord::query()->latest();
        if (! empty($search)) {
            $pronounceQuery->where(function ($q) use ($search) {
                $q->where('word', 'like', "%{$search}%")
                    ->orWhere('meaning', 'like', "%{$search}%")
                    ->orWhere('part_of_speech', 'like', "%{$search}%")
                    ->orWhere('phonetic', 'like', "%{$search}%");
            });
        }
        $pronounceDiffFilter = $request->query('pronounce_diff', '');
        if (! empty($pronounceDiffFilter)) {
            $pronounceQuery->where('difficulty', $pronounceDiffFilter);
        }
        $daftarSoalPronounce = $pronounceQuery->paginate(10, ['*'], 'pronounce_page')->withQueryString();

        return view('guru.mini_game_leaderboard.index', compact(
            'rankedLeaderboard',
            'topPlayer',
            'totalPesertaAktif',
            'totalSoalIkan',
            'totalSoalWerewolf',
            'totalSoalPronounce',
            'daftarSoalIkan',
            'daftarSoalWerewolf',
            'daftarSoalPronounce',
            'werewolfThemes',
            'werewolfThemeFilter',
            'pronounceDiffFilter',
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

        if (! in_array($ext, ['xlsx', 'xls', 'csv', 'txt'])) {
            return redirect()->route('guru.game.index')
                ->withErrors(['file_excel' => 'Format file tidak didukung. Harap gunakan file dengan ekstensi .xlsx, .xls, atau .csv']);
        }

        $result = GameExcelService::importFishing($file->getRealPath(), $ext);

        if (! empty($result['errors']) && $result['imported'] === 0) {
            return redirect()->route('guru.game.index')
                ->withErrors(['file_excel' => implode(' | ', array_slice($result['errors'], 0, 3))]);
        }

        if ($result['imported'] > 0 && $result['skipped'] > 0) {
            $msg = "Import Berhasil! {$result['imported']} butir soal baru ditambahkan. {$result['skipped']} data dilewati karena sudah terdaftar di database (bebas duplikat).";
        } elseif ($result['imported'] > 0) {
            $msg = "Import Berhasil! Seluruh {$result['imported']} butir soal baru berhasil ditambahkan ke Tangkap Ikan.";
        } else {
            $msg = "Tidak ada soal baru yang ditambahkan. Seluruh {$result['skipped']} butir soal dalam file sudah ada di database (anti-duplikat aktif).";
        }

        return redirect()->route('guru.game.index')->with('success', $msg);
    }

    /**
     * Mengunduh Template File Excel (.xlsx) untuk Game Tangkap Ikan dengan Styling Rapi.
     */
    public function downloadFishingTemplate(): StreamedResponse
    {
        $spreadsheet = GameExcelService::generateFishingTemplate();
        $fileName = 'Template_Soal_Tangkap_Ikan.xlsx';

        return response()->streamDownload(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
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

        if (! in_array($ext, ['xlsx', 'xls', 'csv', 'txt'])) {
            return redirect()->route('guru.game.index')
                ->withErrors(['file_excel' => 'Format file tidak didukung. Harap gunakan file dengan ekstensi .xlsx, .xls, atau .csv']);
        }

        $result = GameExcelService::importWerewolf($file->getRealPath(), $ext);

        if (! empty($result['errors']) && $result['imported'] === 0) {
            return redirect()->route('guru.game.index')
                ->withErrors(['file_excel' => implode(' | ', array_slice($result['errors'], 0, 3))]);
        }

        if ($result['imported'] > 0 && $result['skipped'] > 0) {
            $msg = "Import Berhasil! {$result['imported']} pasangan kata baru ditambahkan. {$result['skipped']} pasangan kata dilewati karena sudah ada di database (bebas duplikat).";
        } elseif ($result['imported'] > 0) {
            $msg = "Import Berhasil! Seluruh {$result['imported']} pasangan kata baru berhasil ditambahkan ke Tebak Serigala.";
        } else {
            $msg = "Tidak ada pasangan kata baru yang ditambahkan. Seluruh {$result['skipped']} pasangan kata dalam file sudah terdaftar di database (anti-duplikat aktif).";
        }

        return redirect()->route('guru.game.index')->with('success', $msg);
    }

    /**
     * Mengunduh Template File Excel (.xlsx) untuk Game Tebak Serigala (Wolf vs Sheep) dengan Styling Rapi.
     */
    public function downloadWerewolfTemplate(): StreamedResponse
    {
        $spreadsheet = GameExcelService::generateWerewolfTemplate();
        $fileName = 'Template_Kata_Tebak_Serigala.xlsx';

        return response()->streamDownload(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // ==========================================
    // 3. KONTROL BANK KOSAKATA ADU LAFAL (PRONOUNCE HERO)
    // ==========================================

    public function storePronounceWord(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'word' => ['required', 'string', 'max:100'],
            'phonetic' => ['nullable', 'string', 'max:100'],
            'part_of_speech' => ['required', 'string', 'max:50'],
            'meaning' => ['required', 'string', 'max:255'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'tips' => ['nullable', 'string'],
            'example_sentence' => ['nullable', 'string', 'max:255'],
        ], [
            'word.required' => 'Kosakata bahasa Inggris wajib diisi.',
            'part_of_speech.required' => 'Jenis kata (Part of Speech) wajib dipilih.',
            'meaning.required' => 'Arti kata dalam bahasa Indonesia wajib diisi.',
            'difficulty.required' => 'Tingkat kesulitan wajib dipilih.',
        ]);

        $word = trim($validated['word']);

        // Anti-Duplikat
        if (PronounceWord::whereRaw('UPPER(TRIM(word)) = ?', [strtoupper($word)])->exists()) {
            return redirect()->route('guru.game.index', ['tab' => 'pronounce'])
                ->withErrors(['word' => "Kosakata '{$word}' sudah ada di database. Silakan gunakan kosakata lain untuk menghindari duplikasi!"]);
        }

        PronounceWord::create([
            'word' => $word,
            'phonetic' => $validated['phonetic'] ?? null,
            'part_of_speech' => $validated['part_of_speech'],
            'meaning' => $validated['meaning'],
            'difficulty' => $validated['difficulty'],
            'tips' => $validated['tips'] ?? null,
            'example_sentence' => $validated['example_sentence'] ?? null,
        ]);

        return redirect()->route('guru.game.index', ['tab' => 'pronounce'])
            ->with('success', "Kosakata pelafalan '{$word}' berhasil ditambahkan ke bank soal!");
    }

    public function updatePronounceWord(Request $request, int $id): RedirectResponse
    {
        $wordItem = PronounceWord::findOrFail($id);

        $validated = $request->validate([
            'word' => ['required', 'string', 'max:100'],
            'phonetic' => ['nullable', 'string', 'max:100'],
            'part_of_speech' => ['required', 'string', 'max:50'],
            'meaning' => ['required', 'string', 'max:255'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'tips' => ['nullable', 'string'],
            'example_sentence' => ['nullable', 'string', 'max:255'],
        ]);

        $word = trim($validated['word']);

        // Cek duplikat jika kata diubah
        $duplicate = PronounceWord::whereRaw('UPPER(TRIM(word)) = ?', [strtoupper($word)])
            ->where('id', '!=', $id)
            ->exists();

        if ($duplicate) {
            return redirect()->route('guru.game.index', ['tab' => 'pronounce'])
                ->withErrors(['word' => "Kosakata '{$word}' sudah digunakan oleh data lain."]);
        }

        $wordItem->update([
            'word' => $word,
            'phonetic' => $validated['phonetic'] ?? null,
            'part_of_speech' => $validated['part_of_speech'],
            'meaning' => $validated['meaning'],
            'difficulty' => $validated['difficulty'],
            'tips' => $validated['tips'] ?? null,
            'example_sentence' => $validated['example_sentence'] ?? null,
        ]);

        return redirect()->route('guru.game.index', ['tab' => 'pronounce'])
            ->with('success', "Kosakata pelafalan '{$word}' berhasil diperbarui!");
    }

    public function destroyPronounceWord(int $id): RedirectResponse
    {
        $wordItem = PronounceWord::findOrFail($id);
        $name = $wordItem->word;
        $wordItem->delete();

        return redirect()->route('guru.game.index', ['tab' => 'pronounce'])
            ->with('success', "Kosakata '{$name}' berhasil dihapus dari bank soal!");
    }

    public function importPronounceExcel(Request $request): RedirectResponse
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'max:5120'],
        ], [
            'file_excel.required' => 'Silakan pilih file Excel (.xlsx atau .csv) untuk diunggah.',
        ]);

        $file = $request->file('file_excel');
        $ext = strtolower($file->getClientOriginalExtension());

        if (! in_array($ext, ['xlsx', 'xls', 'csv', 'txt'])) {
            return redirect()->route('guru.game.index', ['tab' => 'pronounce'])
                ->withErrors(['file_excel' => 'Format file tidak didukung. Harap gunakan file dengan ekstensi .xlsx, .xls, atau .csv']);
        }

        $result = GameExcelService::importPronounce($file->getRealPath(), $ext);

        if (! empty($result['errors']) && $result['imported'] === 0) {
            return redirect()->route('guru.game.index', ['tab' => 'pronounce'])
                ->withErrors(['file_excel' => implode(' | ', array_slice($result['errors'], 0, 3))]);
        }

        if ($result['imported'] > 0 && $result['skipped'] > 0) {
            $msg = "Import Berhasil! {$result['imported']} kosakata pelafalan baru ditambahkan. {$result['skipped']} data dilewati karena sudah ada di database (bebas duplikat).";
        } elseif ($result['imported'] > 0) {
            $msg = "Import Berhasil! Seluruh {$result['imported']} kosakata pelafalan baru berhasil ditambahkan ke bank soal.";
        } else {
            $msg = "Tidak ada kosakata baru yang ditambahkan. Seluruh {$result['skipped']} kosakata dalam file sudah terdaftar di database (anti-duplikat aktif).";
        }

        return redirect()->route('guru.game.index', ['tab' => 'pronounce'])->with('success', $msg);
    }

    public function downloadPronounceTemplate(): StreamedResponse
    {
        $spreadsheet = GameExcelService::generatePronounceTemplate();
        $fileName = 'Template_Kosakata_Pelafalan_Adu_Lafal.xlsx';

        return response()->streamDownload(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
