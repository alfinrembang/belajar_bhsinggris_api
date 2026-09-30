<x-layout 
    title="Mini Games & Leaderboard - Study English Prima" 
    pageTitle="Mini Games & Leaderboard" 
    pageSubtitle="Pantau performa permainan edukasi interaktif, poin XP siswa, dan papan peringkat." 
    active="game"
>
    <!-- Bar Aksi Atas: Pencarian, Filter & Tombol Buat Tantangan -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs">
        <!-- Pencarian -->
        <div class="relative w-full md:w-80">
            <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" 
                   placeholder="Cari siswa atau nama game..." 
                   class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
        </div>

        <!-- Filter & Tombol -->
        <div class="flex items-center gap-3">
            <select class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="">Semua Periode</option>
                <option value="weekly">Minggu Ini</option>
                <option value="monthly">Bulan Ini</option>
                <option value="all">Semua Waktu</option>
            </select>

            <!-- Tombol Buat Tantangan -->
            <a href="#" 
               class="px-5 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-semibold text-xs shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Buat Event Game
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik Game & Leaderboard -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg shrink-0">
                🎮
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Mini Game Tersedia</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">3 Mode Game</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg shrink-0">
                🏆
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Top Rank #1</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5 truncate max-w-[150px]">
                    {{ $daftarSiswa->first()->nama_lengkap ?? 'Belum ada' }}
                </h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-lg shrink-0">
                ⚡
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Peserta Aktif</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ count($daftarSiswa ?? []) }} Siswa</h4>
            </div>
        </div>
    </div>

    <!-- Layout 2 Kolom: Daftar Mode Game (Kiri) & Top Leaderboard (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri (2 Span): Daftar Mode Mini Game -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Mode Permainan Edukasi</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Dapat dimainkan oleh siswa melalui aplikasi Flutter</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">
                        Semua Aktif
                    </span>
                </div>

                <div class="space-y-3.5">
                    <!-- Game 1: Word Scramble -->
                    <div class="p-4.5 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-[#1E6BFF]/30 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-[#1E6BFF] flex items-center justify-center font-bold text-xl shrink-0">
                                🧩
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Word Scramble (Susun Kata)</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Siswa menyusun huruf acak menjadi kosakata bahasa Inggris yang benar.</p>
                                <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-400">
                                    <span class="font-semibold text-slate-600">🎯 Level: Mudah - Menengah</span>
                                    <span>•</span>
                                    <span class="text-[#1E6BFF] font-semibold">+50 XP / Kata</span>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">Tersedia</span>
                        </div>
                    </div>

                    <!-- Game 2: Flashcard Match -->
                    <div class="p-4.5 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-[#1E6BFF]/30 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-xl shrink-0">
                                🃏
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Vocabulary Flashcard Match</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Mencocokkan kata dalam bahasa Inggris dengan terjemahan bahasa Indonesia yang tepat.</p>
                                <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-400">
                                    <span class="font-semibold text-slate-600">🎯 Level: Pemula</span>
                                    <span>•</span>
                                    <span class="text-[#1E6BFF] font-semibold">+30 XP / Pasang</span>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">Tersedia</span>
                        </div>
                    </div>

                    <!-- Game 3: Speed Listening Quiz -->
                    <div class="p-4.5 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-[#1E6BFF]/30 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-xl shrink-0">
                                🎧
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Speed Listening Audio Challenge</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Mendengarkan penggalan audio pendek dan memilih jawaban tepat secepat mungkin.</p>
                                <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-400">
                                    <span class="font-semibold text-slate-600">🎯 Level: Menengah</span>
                                    <span>•</span>
                                    <span class="text-[#1E6BFF] font-semibold">+100 XP / Sesi</span>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">Tersedia</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan (1 Span): Leaderboard Papan Peringkat -->
        <div class="space-y-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <span>🏆</span> Papan Peringkat
                    </h3>
                    <span class="text-xs font-semibold text-[#1E6BFF]">Top Siswa</span>
                </div>

                <div class="space-y-3">
                    @forelse($daftarSiswa ?? [] as $rank => $siswa)
                        @php
                            $medalEmoji = match($rank) {
                                0 => '🥇',
                                1 => '🥈',
                                2 => '🥉',
                                default => ($rank + 1)
                            };
                            $score = 1250 - ($rank * 110);
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-2xl {{ $rank === 0 ? 'bg-amber-50/70 border border-amber-200/60' : 'bg-slate-50/60 border border-slate-100/70' }}">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-6 text-center font-bold text-sm shrink-0">
                                    {{ $medalEmoji }}
                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold text-xs text-slate-800 truncate">{{ $siswa->nama_lengkap }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">{{ $siswa->kelas_lengkap ?: 'Siswa' }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-mono font-bold text-xs text-[#1E6BFF]">{{ max(150, $score) }}</span>
                                <p class="text-[9px] font-semibold text-slate-400">XP</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-slate-400 py-6">Belum ada skor permainan tersimpan.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-layout>
