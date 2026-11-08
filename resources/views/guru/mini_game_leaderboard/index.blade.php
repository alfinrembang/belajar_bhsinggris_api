<x-layout 
    title="Mini Games & Leaderboard - Study English Prima" 
    pageTitle="Mini Games & Leaderboard" 
    pageSubtitle="Kelola konten soal game edukasi, pantau performa permainan, dan papan peringkat siswa." 
    active="game"
>
    <!-- Notifikasi Flash Message Sukses / Error -->
    @if(session('success'))
        <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <span class="text-base">✨</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium space-y-1 shadow-xs">
            <div class="font-bold flex items-center gap-2">
                <span>⚠️</span> Mohon periksa kesalahan input berikut:
            </div>
            <ul class="list-disc list-inside pl-4 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Bar Aksi Atas: Pencarian, Filter & Tombol Aksi Game -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs">
        <!-- Pencarian -->
        <form method="GET" action="{{ route('guru.game.index') }}" class="relative w-full md:w-80">
            <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" 
                   name="q"
                   value="{{ $search ?? '' }}"
                   placeholder="Cari siswa atau kosakata game..." 
                   class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
        </form>

        <!-- Aksi Guru: Rekap Puzzle & Tambah Soal Tangkap Ikan -->
        <div class="flex items-center gap-3">
            <button type="button"
                    onclick="openModal('modalPuzzleRekap')"
                    class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-all flex items-center gap-2 shrink-0 cursor-pointer">
                <span>🧩</span>
                Rekap Puzzle
            </button>

            <!-- Tombol Tambah Soal Kosakata Game Tangkap Ikan -->
            <button type="button"
                    onclick="openModal('modalTambahSoalIkan')"
                    class="px-5 py-2.5 rounded-2xl bg-[#4EA87D] hover:bg-[#43966E] text-white font-semibold text-xs shadow-[0_6px_16px_rgba(78,168,125,0.28)] transition-all flex items-center gap-2 shrink-0 cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                + Tambah Soal Tangkap Ikan
            </button>
        </div>
    </div>

    <!-- Ringkasan Statistik Game & Leaderboard Dinamis -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xl shrink-0">
                🎮
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Mini Game Tersedia</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">2 Mode Game</h4>
                <p class="text-[11px] text-slate-400">Puzzle Master & Tangkap Ikan</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl shrink-0">
                🏆
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Top Rank #1</p>
                <h4 class="text-lg font-bold text-slate-800 mt-0.5 truncate">
                    {{ $topPlayer['nama'] ?? 'Belum ada data' }}
                </h4>
                <p class="text-[11px] font-semibold text-[#4EA87D]">
                    {{ number_format($topPlayer['total_skor'] ?? 0) }} Poin Game
                </p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] text-[#4EA87D] flex items-center justify-center font-bold text-xl shrink-0">
                ⚡
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Siswa Aktif</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ $totalPesertaAktif }} Siswa</h4>
                <p class="text-[11px] text-slate-400">{{ $totalSoalIkan }} Soal Kosakata Aktif</p>
            </div>
        </div>
    </div>

    <!-- Layout 2 Kolom: Manajemen Mode Game & Bank Soal (Kiri) & Top Leaderboard (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri (2 Span): Mode Permainan Edukasi Resmi Mobile & Bank Soal Collapsible -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Mode Permainan Edukasi Siswa</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Sesuai dengan 2 permainan interaktif di aplikasi mobile Flutter</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 font-bold text-xs">
                        2 Game Aktif
                    </span>
                </div>

                <div class="space-y-4">
                    <!-- Game 1: Tangkap Ikan (Word Fishing) dengan Panel Bank Soal Collapsible di Bawahnya -->
                    <div class="rounded-2xl border border-sky-100 bg-sky-50/40 hover:border-sky-300 transition-all overflow-hidden">
                        
                        <!-- Header & Info Game Tangkap Ikan -->
                        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-13 h-13 rounded-2xl bg-sky-500 text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                                    🐠
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-slate-800 text-sm">Tangkap Ikan (Word Fishing)</h4>
                                        <span class="px-2 py-0.5 rounded-full bg-sky-100 text-sky-700 text-[10px] font-bold">Vocabulary</span>
                                    </div>
                                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                        Siswa menangkap ikan chibi berenang di akuarium realistis dengan timer 1 menit, dan menjawab tantangan kosakata (arti kata, jenis kata / part of speech).
                                    </p>
                                    <div class="flex flex-wrap items-center gap-3 mt-2.5 text-[11px] text-slate-500">
                                        <span class="font-semibold text-slate-700">🌊 9 Level Akuarium</span>
                                        <span>•</span>
                                        <span class="font-semibold text-slate-700">⏱️ Batas 1 Menit</span>
                                        <span>•</span>
                                        <span class="text-sky-600 font-bold">⭐ {{ $totalSoalIkan }} Soal Terdaftar</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tombol Aksi Tangkap Ikan -->
                            <div class="flex sm:flex-col gap-2 shrink-0">
                                <button type="button"
                                        onclick="openModal('modalTambahSoalIkan')"
                                        class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold shadow-xs transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    + Soal Baru
                                </button>
                                
                                <!-- Tombol Buka/Tutup Bank Soal (Accordion Toggle) -->
                                <button type="button"
                                        id="btnToggleBankSoal"
                                        onclick="toggleBankSoal()"
                                        class="px-3.5 py-2 rounded-xl bg-white border border-sky-200 text-sky-700 hover:bg-sky-100/60 text-xs font-semibold transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                                    <span id="textToggleBankSoal">
                                        {{ (request()->has('level') || request()->has('page') || session('success') || $errors->any()) ? 'Tutup Bank Soal' : 'Kelola Bank Soal' }}
                                    </span>
                                    <svg id="iconToggleBankSoal" class="w-3.5 h-3.5 transition-transform duration-200 {{ (request()->has('level') || request()->has('page') || session('success') || $errors->any()) ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- PANEL ACCORDION: Bank Soal Tangkap Ikan (Tepat di Bawah Menu Game Fishing) -->
                        <div id="panelBankSoalIkan" class="{{ (request()->has('level') || request()->has('page') || session('success') || $errors->any()) ? '' : 'hidden' }} border-t border-sky-100 bg-white p-5 transition-all">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                                        <span>🐠</span> Daftar Soal Kosakata Game Tangkap Ikan
                                        <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 text-[10px] font-bold">
                                            {{ $totalSoalIkan }} Soal
                                        </span>
                                    </h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Soal yang Anda buat langsung tersinkronisasi ke aplikasi mobile Flutter siswa</p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <!-- Filter Level -->
                                    <form method="GET" action="{{ route('guru.game.index') }}" class="flex items-center gap-2">
                                        @if(!empty($search))
                                            <input type="hidden" name="q" value="{{ $search }}">
                                        @endif
                                        <select name="level" 
                                                onchange="this.form.submit()"
                                                class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-700 outline-none cursor-pointer">
                                            <option value="">Semua Level (1-9)</option>
                                            @for($i = 1; $i <= 9; $i++)
                                                <option value="{{ $i }}" {{ ($levelFilter ?? '') == $i ? 'selected' : '' }}>Level {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </form>

                                    <button type="button"
                                            onclick="openModal('modalTambahSoalIkan')"
                                            class="px-3 py-1.5 rounded-xl bg-[#4EA87D] hover:bg-[#43966E] text-white font-semibold text-xs transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        + Soal
                                    </button>
                                </div>
                            </div>

                            <!-- Tabel Soal Ikan -->
                            <div class="overflow-x-auto rounded-xl border border-slate-100">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                            <th class="py-2.5 px-3">Level</th>
                                            <th class="py-2.5 px-3">Kosakata (Word)</th>
                                            <th class="py-2.5 px-3">Part of Speech</th>
                                            <th class="py-2.5 px-3">Arti (Jawaban Benar)</th>
                                            <th class="py-2.5 px-3">Pilihan Jawaban</th>
                                            <th class="py-2.5 px-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                                        @forelse($daftarSoalIkan as $soal)
                                            <tr class="hover:bg-slate-50/70 transition-colors">
                                                <td class="py-2.5 px-3 whitespace-nowrap">
                                                    <span class="px-2 py-0.5 rounded-md bg-sky-50 text-sky-700 font-bold text-[10px]">
                                                        Lvl {{ $soal->level }}
                                                    </span>
                                                    <span class="block text-[9px] text-slate-400 capitalize mt-0.5">{{ $soal->difficulty }}</span>
                                                </td>
                                                <td class="py-2.5 px-3 font-bold text-slate-800 whitespace-nowrap">
                                                    {{ $soal->word }}
                                                </td>
                                                <td class="py-2.5 px-3 whitespace-nowrap">
                                                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-semibold">
                                                        {{ $soal->part_of_speech }}
                                                    </span>
                                                </td>
                                                <td class="py-2.5 px-3 font-medium text-emerald-700">
                                                    {{ $soal->meaning }}
                                                </td>
                                                <td class="py-2.5 px-3">
                                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                                        @foreach($soal->options ?? [] as $opt)
                                                            <span class="px-1.5 py-0.5 rounded bg-slate-100 text-[10px] text-slate-600 truncate max-w-[110px]">
                                                                {{ $opt }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                                    <form method="POST" action="{{ route('guru.game.fishing.destroy', $soal->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal kosakata {{ $soal->word }}?')">
                                                        @csrf
                                                        <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Soal">
                                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-6 text-center text-slate-400 text-xs">
                                                    Belum ada soal kosakata memancing untuk filter ini. Klik tombol "+ Soal" untuk membuat soal baru.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Footer Accordion & Pagination -->
                            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                                <div>
                                    @if($daftarSoalIkan->hasPages())
                                        {{ $daftarSoalIkan->links() }}
                                    @endif
                                </div>
                                <button type="button" 
                                        onclick="toggleBankSoal()"
                                        class="text-xs text-slate-400 hover:text-slate-600 font-semibold flex items-center gap-1 cursor-pointer">
                                    <span>Tutup Bank Soal</span>
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Game 2: Puzzle Master -->
                    <div class="p-5 rounded-2xl border border-amber-100 bg-amber-50/40 hover:bg-white hover:border-amber-300 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-13 h-13 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                                🧩
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-slate-800 text-sm">Puzzle Master</h4>
                                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">Visual & Spatial</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                    Siswa menyusun kepingan puzzle gambar objek dan landmark dunia untuk melatih daya ingat visual, ketelitian, dan pengenalan kosakata bahasa Inggris.
                                </p>
                                <div class="flex flex-wrap items-center gap-3 mt-2.5 text-[11px] text-slate-500">
                                    <span class="font-semibold text-slate-700">🧩 9 Level Puzzle</span>
                                    <span>•</span>
                                    <span class="font-semibold text-slate-700">Mudah (3x3), Sedang (4x4), Sulit (5x5)</span>
                                    <span>•</span>
                                    <span class="text-amber-600 font-bold">🏆 {{ $totalPuzzleSelesai }} Sesi Tuntas</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex sm:flex-col gap-2 shrink-0">
                            <button type="button"
                                    onclick="openModal('modalPuzzleRekap')"
                                    class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-xs transition-all text-center cursor-pointer">
                                Rekap Capaian
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Kolom Kanan (1 Span): Leaderboard Papan Peringkat Real-Time -->
        <div class="space-y-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <span>🏆</span> Papan Peringkat Game
                    </h3>
                    <span class="text-xs font-semibold text-[#4EA87D]">Skor Nyata</span>
                </div>

                <div class="space-y-3">
                    @forelse($rankedLeaderboard as $item)
                        @php
                            $medalEmoji = match($item['rank']) {
                                1 => '🥇',
                                2 => '🥈',
                                3 => '🥉',
                                default => $item['rank']
                            };
                            $isTop3 = $item['rank'] <= 3;
                            $bgClass = match($item['rank']) {
                                1 => 'bg-amber-50/70 border-amber-200/80',
                                2 => 'bg-slate-100/60 border-slate-200/80',
                                3 => 'bg-amber-100/30 border-amber-200/50',
                                default => 'bg-slate-50/60 border-slate-100/70'
                            };
                        @endphp
                        <div class="flex items-center justify-between p-3.5 rounded-2xl border {{ $bgClass }} transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-6 text-center font-bold text-sm shrink-0">
                                    {{ $medalEmoji }}
                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold text-xs text-slate-800 truncate">{{ $item['nama'] }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">{{ $item['kelas'] }} • No. {{ $item['no_absen'] }}</p>
                                    <div class="flex items-center gap-2 mt-1 text-[10px] text-slate-500">
                                        <span>🐠 {{ $item['skor_memancing'] }} pts</span>
                                        <span>•</span>
                                        <span>🧩 {{ $item['puzzle_selesai'] }} lvl</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-mono font-bold text-xs text-[#4EA87D]">
                                    {{ number_format($item['total_skor']) }}
                                </span>
                                <p class="text-[9px] font-semibold text-slate-400 uppercase">Poin</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-slate-400 py-6">Belum ada siswa yang memainkan game.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL 1: Tambah Soal Kosakata Game Tangkap Ikan (Pop-up Form) -->
    <div id="modalTambahSoalIkan" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-3xl p-6 shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🐠</span>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Tambah Soal Tangkap Ikan</h3>
                        <p class="text-[11px] text-slate-400">Kosakata akan langsung muncul saat siswa bermain di mobile</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalTambahSoalIkan')" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">&times;</button>
            </div>

            <form method="POST" action="{{ route('guru.game.fishing.store') }}" class="space-y-4">
                @csrf

                <!-- Level & Kesulitan -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Level Akuarium (1-9)</label>
                        <select name="level" required class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                            @for($lvl = 1; $lvl <= 9; $lvl++)
                                <option value="{{ $lvl }}">Level {{ $lvl }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Kesulitan</label>
                        <select name="difficulty" required class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                            <option value="mudah">Mudah</option>
                            <option value="sedang">Sedang</option>
                            <option value="sulit">Sulit</option>
                        </select>
                    </div>
                </div>

                <!-- Kosakata (Word) & Jenis Kata -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kosakata (English Word)</label>
                        <input type="text" 
                               name="word" 
                               required 
                               placeholder="Contoh: GENEROUS" 
                               class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 uppercase focus:bg-white focus:border-[#4EA87D] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Kata (Part of Speech)</label>
                        <select name="part_of_speech" required class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                            <option value="Adjective (Kata Sifat)">Adjective (Kata Sifat)</option>
                            <option value="Verb (Kata Kerja)">Verb (Kata Kerja)</option>
                            <option value="Noun (Kata Benda)">Noun (Kata Benda)</option>
                            <option value="Adverb (Kata Keterangan)">Adverb (Kata Keterangan)</option>
                        </select>
                    </div>
                </div>

                <!-- Arti Kata / Jawaban Benar -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Arti Kata / Jawaban Benar (Bahasa Indonesia)</label>
                    <input type="text" 
                           name="correct_answer" 
                           required 
                           placeholder="Contoh: Dermawan / Suka Memberi" 
                           class="w-full px-3 py-2 rounded-xl bg-emerald-50/70 border border-emerald-200 text-xs font-medium text-emerald-900 focus:bg-white focus:border-emerald-500 outline-none">
                    <input type="hidden" name="meaning" id="hiddenMeaningInput" value="">
                </div>

                <!-- 3 Pilihan Pengecoh (Distractors) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">3 Pilihan Pengecoh (Jawaban Salah)</label>
                    <div class="space-y-2">
                        <input type="text" name="option_2" required placeholder="Pengecoh 1 (contoh: Pelit / Kikir)" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                        <input type="text" name="option_3" required placeholder="Pengecoh 2 (contoh: Pemarah)" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                        <input type="text" name="option_4" required placeholder="Pengecoh 3 (contoh: Penakut)" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                    </div>
                </div>

                <!-- Contoh Kalimat Praktis -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Contoh Kalimat (Opsional)</label>
                    <input type="text" 
                           name="example_sentence" 
                           placeholder="Contoh: Mr. Jack is a generous teacher." 
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                </div>

                <!-- Penjelasan Edukasi -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Penjelasan Singkat (Opsional)</label>
                    <textarea name="explanation" 
                              rows="2" 
                              placeholder="Penjelasan mengapa kata tersebut memiliki arti demikian..."
                              class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" 
                            onclick="closeModal('modalTambahSoalIkan')" 
                            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                            onclick="document.getElementById('hiddenMeaningInput').value = document.querySelector('input[name=correct_answer]').value"
                            class="px-5 py-2 rounded-xl bg-[#4EA87D] hover:bg-[#43966E] text-white text-xs font-semibold shadow-xs cursor-pointer">
                        Simpan Soal Kosakata
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: Rekap Capaian & Best Time Game Puzzle Siswa -->
    <div id="modalPuzzleRekap" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-3xl p-6 shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🧩</span>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Rekap Progres Puzzle Master</h3>
                        <p class="text-[11px] text-slate-400">Daftar siswa yang telah menuntaskan level puzzle beserta waktu terbaiknya</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalPuzzleRekap')" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">&times;</button>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4">Level</th>
                            <th class="py-3 px-4">Tingkat</th>
                            <th class="py-3 px-4 text-center">Bintang</th>
                            <th class="py-3 px-4 text-right">Waktu Terbaik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($puzzleProgresList as $prog)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 font-bold text-slate-800">
                                    {{ $prog->siswa->nama_lengkap ?? 'Siswa' }}
                                    <span class="block font-normal text-[10px] text-slate-400">{{ $prog->siswa->kelas_lengkap ?? '' }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-bold text-[10px]">
                                        Level {{ $prog->level }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 capitalize font-medium">
                                    {{ $prog->difficulty }}
                                </td>
                                <td class="py-3 px-4 text-center text-amber-500 font-bold">
                                    {{ str_repeat('⭐', $prog->stars) }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-slate-800">
                                    {{ $prog->best_time ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    Belum ada catatan penyelesaian puzzle oleh siswa.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="button" 
                        onclick="closeModal('modalPuzzleRekap')" 
                        class="px-5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Script Modal & Accordion Buka-Tutup Bank Soal -->
    <script>
        function openModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('hidden');
        }
        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        }

        // Buka / Tutup Panel Bank Soal Tangkap Ikan
        function toggleBankSoal() {
            const panel = document.getElementById('panelBankSoalIkan');
            const btnText = document.getElementById('textToggleBankSoal');
            const btnIcon = document.getElementById('iconToggleBankSoal');

            if (!panel) return;

            if (panel.classList.contains('hidden')) {
                panel.classList.remove('hidden');
                if (btnText) btnText.textContent = 'Tutup Bank Soal';
                if (btnIcon) btnIcon.classList.add('rotate-180');
            } else {
                panel.classList.add('hidden');
                if (btnText) btnText.textContent = 'Kelola Bank Soal';
                if (btnIcon) btnIcon.classList.remove('rotate-180');
            }
        }
    </script>
</x-layout>
