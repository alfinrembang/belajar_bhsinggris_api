<div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.02)] space-y-6">
    
    <!-- Header Bagian Mode Permainan -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#EAF5EF] text-[#2E7352] flex items-center justify-center shrink-0 border border-[#D7EDE1]">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                </svg>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Mode Permainan Edukasi Siswa</h3>
                <p class="text-xs text-slate-400 mt-0.5">Kontrol konten soal, pasangan kata, dan parameter permainan pada aplikasi mobile Flutter.</p>
            </div>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] text-xs font-bold self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-[#4EA87D] animate-pulse"></span>
            3 Mode Game Aktif
        </span>
    </div>

    @php
        $initialGamePage = request()->get('game_page') == 2 || request()->get('tab') === 'pronounce' || request()->has('pronounce_diff') || request()->has('pronounce_page') ? 2 : 1;
    @endphp

    <!-- HALAMAN 1 (MAKSIMAL 2 GAME KE BAWAH: TANGKAP IKAN & TEBAK SERIGALA) -->
    <div id="gamePage1" class="game-page space-y-6 {{ $initialGamePage == 2 ? 'hidden' : '' }}">
        
        <!-- ========================================== -->
        <!-- GAME 1: Tangkap Ikan (Word Fishing) -->
        <!-- ========================================== -->
        <div class="rounded-2xl border border-slate-200/80 bg-white hover:border-[#4EA87D]/40 transition-all overflow-hidden shadow-xs">
            
            <!-- Header Kartu Game Tangkap Ikan -->
            <div class="p-5 lg:p-6 bg-gradient-to-r from-[#EAF5EF]/50 via-white to-white flex flex-col sm:flex-row sm:items-center justify-between gap-5 border-b border-slate-100">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#3E946B] text-white flex items-center justify-center shadow-xs shrink-0">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 12c-2.5 0-4-2.5-4-5 3.5 0 6 2 8 5"></path>
                            <path d="M18 12c2.5 0 4-2.5 4-5-3.5 0-6 2-8 5"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                        </svg>
                    </div>
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="font-extrabold text-slate-900 text-base tracking-tight">Tangkap Ikan (Word Fishing)</h4>
                            <span class="px-2.5 py-0.5 rounded-full bg-[#EAF5EF] text-[#2E7352] text-[11px] font-bold border border-[#D7EDE1]">Vocabulary & Grammar</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#4EA87D]"></span> Live di Mobile
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Latihan kosakata interaktif berbasis akuarium 3D. Siswa mengidentifikasi arti kata dan kategori <span class="font-semibold text-slate-700">Part of Speech</span> dalam batas waktu 60 detik untuk memperoleh skor serta koin reward.
                        </p>
                        
                        <!-- Chips Metadata Game -->
                        <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px]">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold border border-slate-200/70">
                                <svg class="w-3.5 h-3.5 text-[#2E7352]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                9 Level Akuarium
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold border border-slate-200/70">
                                <svg class="w-3.5 h-3.5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                Timer 60 Detik
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#EAF5EF] text-[#2E7352] font-bold border border-[#D7EDE1]">
                                <svg class="w-3.5 h-3.5 text-[#2E7352]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                {{ $totalSoalIkan }} Soal Terdaftar
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Kontrol Game Tangkap Ikan -->
                <div class="flex flex-wrap sm:flex-col gap-2 shrink-0">
                    <x-button variant="create" size="sm" type="button" onclick="openModal('modalTambahSoalIkan')" class="!shadow-xs !rounded-xl">
                        Tambah Soal
                    </x-button>

                    <div class="flex items-center gap-1.5">
                        <button type="button" 
                                onclick="openModal('modalImportFishing')"
                                title="Import dari Excel (.xlsx / .csv)"
                                class="px-2.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <span>Import</span>
                        </button>
                        <a href="{{ route('guru.game.fishing.template') }}" 
                           title="Unduh Template Excel (.xlsx)"
                           class="px-2 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 text-xs font-bold transition-all flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        </a>
                    </div>
                    
                    <!-- Tombol Accordion Buka/Tutup Bank Soal -->
                    <button type="button"
                            id="btnToggleBankSoal"
                            onclick="toggleBankSoal()"
                            class="px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 text-xs font-bold transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                        <span id="textToggleBankSoal">
                            {{ (request()->has('level') || request()->has('fishing_page') || session('success') || $errors->any()) ? 'Tutup Bank Soal' : 'Kelola Bank Soal' }}
                        </span>
                        <svg id="iconToggleBankSoal" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200 {{ (request()->has('level') || request()->has('fishing_page') || session('success') || $errors->any()) ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- PANEL ACCORDION: Bank Soal Tangkap Ikan -->
            <div id="panelBankSoalIkan" class="{{ (request()->has('level') || request()->has('fishing_page')) ? '' : 'hidden' }} bg-white p-5 lg:p-6 transition-all">
                
                <!-- Subheader Panel Bank Soal -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-4 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="font-extrabold text-slate-900 text-sm">Daftar Kosakata Game Tangkap Ikan</h4>
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold">
                                {{ $totalSoalIkan }} Butir
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Seluruh butir soal langsung tersinkronisasi otomatis ke aplikasi mobile Flutter siswa.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Filter Level Dropdown -->
                        <form method="GET" action="{{ route('guru.game.index') }}" class="flex items-center gap-2">
                            @if(!empty($search))
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif
                            @if(!empty($rombelFilter))
                                <input type="hidden" name="rombel" value="{{ $rombelFilter }}">
                            @endif
                            <select name="level" 
                                    onchange="this.form.submit()"
                                    class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:bg-white focus:border-[#4EA87D] cursor-pointer">
                                <option value="">📁 Semua Level (1-9)</option>
                                @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}" {{ ($levelFilter ?? '') == $i ? 'selected' : '' }}>⭐ Level {{ $i }}</option>
                                @endfor
                            </select>
                        </form>

                        <a href="{{ route('guru.game.fishing.template') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            <span>Unduh Template (.xlsx)</span>
                        </a>

                        <button type="button" onclick="openModal('modalImportFishing')" class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <span>Import Excel</span>
                        </button>

                        <x-button variant="create" size="sm" type="button" onclick="openModal('modalTambahSoalIkan')" class="!rounded-xl">
                            Soal Baru
                        </x-button>
                    </div>
                </div>

                <!-- Tabel Butir Soal Kosakata -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-4">Level</th>
                                <th class="py-3 px-4">Kosakata (Word)</th>
                                <th class="py-3 px-4">Part of Speech</th>
                                <th class="py-3 px-4">Arti (Kunci Jawaban)</th>
                                <th class="py-3 px-4">Pilihan Pengecoh</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            @forelse($daftarSoalIkan as $soal)
                                @php
                                    $posColor = match(strtolower(trim($soal->part_of_speech))) {
                                        'noun (kata benda)', 'noun' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'verb (kata kerja)', 'verb' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'adjective (kata sifat)', 'adjective' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'adverb (kata keterangan)', 'adverb' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-sky-50 text-sky-700 font-bold text-[11px] border border-sky-200/70">
                                            Lvl {{ $soal->level }}
                                        </span>
                                        <span class="block text-[10px] text-slate-400 capitalize mt-0.5 font-medium">{{ $soal->difficulty }}</span>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="font-mono font-extrabold text-slate-900 text-sm tracking-wide">
                                            {{ strtoupper($soal->word) }}
                                        </span>
                                        @if(!empty($soal->example_sentence))
                                            <p class="text-[10px] text-slate-400 italic truncate max-w-xs mt-0.5">
                                                "{{ $soal->example_sentence }}"
                                            </p>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $posColor }}">
                                            {{ $soal->part_of_speech }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-emerald-700 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            <span>{{ $soal->meaning }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                            @foreach($soal->options ?? [] as $opt)
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-[10px] text-slate-600 font-medium truncate max-w-[120px]">
                                                    {{ $opt }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <form method="POST" action="{{ route('guru.game.fishing.destroy', $soal->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus butir soal kosakata {{ $soal->word }}?')">
                                            @csrf
                                            <x-button variant="delete" type="submit" iconOnly size="sm" title="Hapus Butir Soal" class="!p-1.5 !rounded-lg" />
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center">
                                        <div class="max-w-sm mx-auto space-y-2">
                                            <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                            </div>
                                            <p class="text-xs font-semibold text-slate-700">Tidak ada butir soal kosakata ditemukan</p>
                                            <p class="text-[11px] text-slate-400">Silakan sesuaikan filter level atau klik tombol "Soal Baru" / "Import Excel".</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Accordion & Pagination -->
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                    <div class="flex-1 w-full">
                        @if($daftarSoalIkan->total() > 0)
                            <x-pagination :paginator="$daftarSoalIkan" />
                        @endif
                    </div>
                    <button type="button" 
                            onclick="toggleBankSoal()"
                            class="text-xs text-slate-400 hover:text-slate-600 font-semibold flex items-center gap-1.5 cursor-pointer shrink-0">
                        <span>Tutup Bank Soal</span>
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
                    </button>
                </div>

            </div>

        </div>

        <!-- ========================================== -->
        <!-- GAME 2: Tebak Serigala (Wolf vs Sheep) -->
        <!-- ========================================== -->
        <div class="rounded-2xl border border-slate-200/80 bg-white hover:border-[#2E7352]/40 transition-all overflow-hidden shadow-xs">
            
            <!-- Header Kartu Game Tebak Serigala -->
            <div class="p-5 lg:p-6 bg-gradient-to-r from-[#EAF5EF]/40 via-white to-white flex flex-col sm:flex-row sm:items-center justify-between gap-5 border-b border-slate-100">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#2E7352] text-white flex items-center justify-center shadow-xs shrink-0">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="font-extrabold text-slate-900 text-base tracking-tight">Tebak Serigala (Wolf vs Sheep)</h4>
                            <span class="px-2.5 py-0.5 rounded-full bg-[#EAF5EF] text-[#2E7352] text-[11px] font-bold border border-[#D7EDE1]">Speaking & Deduction</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold border border-slate-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#4EA87D]"></span> Room Mabar 10 Orang & AI Bot
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Permainan deduksi sosial interaktif 10 pemain (9 Domba vs 1 Serigala). Melatih kemampuan siswa dalam merangkai <span class="font-semibold text-slate-700">Descriptive Clues</span> dalam bahasa Inggris serta mengungkap serigala yang menyamar lewat voting musyawarah.
                        </p>
                        
                        <!-- Chips Metadata Game -->
                        <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px]">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold border border-slate-200/70">
                                <svg class="w-3.5 h-3.5 text-[#2E7352]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                Format: 9 Domba vs 1 Serigala (10 Pemain)
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold border border-slate-200/70">
                                <svg class="w-3.5 h-3.5 text-[#2E7352]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                Room Kode 6-Digit & AI Bot Kalem
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#EAF5EF] text-[#2E7352] font-bold border border-[#D7EDE1]">
                                <svg class="w-3.5 h-3.5 text-[#2E7352]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                {{ $totalSoalWerewolf ?? 0 }} Pasangan Kata Kartu
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Kontrol Game Tebak Serigala -->
                <div class="flex flex-wrap sm:flex-col gap-2 shrink-0">
                    <x-button variant="create" size="sm" type="button" onclick="openModal('modalTambahSoalWerewolf')" class="!shadow-xs !rounded-xl">
                        Tambah Pasangan
                    </x-button>

                    <div class="flex items-center gap-1.5">
                        <button type="button" 
                                onclick="openModal('modalImportWerewolf')"
                                title="Import dari Excel (.xlsx / .csv)"
                                class="px-2.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <span>Import</span>
                        </button>
                        <a href="{{ route('guru.game.werewolf.template') }}" 
                           title="Unduh Template Excel (.xlsx)"
                           class="px-2 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 text-xs font-bold transition-all flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        </a>
                    </div>
                    
                    <!-- Tombol Accordion Buka/Tutup Bank Kata Werewolf -->
                    <button type="button"
                            id="btnToggleBankWerewolf"
                            onclick="toggleBankWerewolf()"
                            class="px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 text-xs font-bold transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                        <span id="textToggleBankWerewolf">
                            {{ (request()->has('werewolf_theme') || request()->has('werewolf_page')) ? 'Tutup Bank Kata' : 'Kelola Bank Kata' }}
                        </span>
                        <svg id="iconToggleBankWerewolf" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200 {{ (request()->has('werewolf_theme') || request()->has('werewolf_page')) ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- PANEL ACCORDION: Bank Kata Tebak Serigala (Wolf vs Sheep) -->
            <div id="panelBankWerewolf" class="{{ (request()->has('werewolf_theme') || request()->has('werewolf_page')) ? '' : 'hidden' }} bg-white p-5 lg:p-6 transition-all">
                
                <!-- Subheader Panel Bank Kata -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-4 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="font-extrabold text-slate-900 text-sm">Daftar Pasangan Kata Game Tebak Serigala</h4>
                            <span class="px-2 py-0.5 rounded-full bg-[#EAF5EF] text-[#2E7352] border border-[#D7EDE1] text-[11px] font-bold">
                                {{ $totalSoalWerewolf ?? 0 }} Pasang
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Guru dapat mengelola 2 kartu jawaban (Kata Domba vs Kata Serigala), petunjuk, dan perbedaannya.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Filter Kategori / Tema Dropdown -->
                        <form method="GET" action="{{ route('guru.game.index') }}" class="flex items-center gap-2">
                            @if(!empty($search))
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif
                            @if(!empty($rombelFilter))
                                <input type="hidden" name="rombel" value="{{ $rombelFilter }}">
                            @endif
                            <select name="werewolf_theme" 
                                    onchange="this.form.submit()"
                                    class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:bg-white focus:border-[#4EA87D] cursor-pointer">
                                <option value="">📁 Semua Kategori</option>
                                @foreach($werewolfThemes ?? [] as $theme)
                                    <option value="{{ $theme }}" {{ ($werewolfThemeFilter ?? '') == $theme ? 'selected' : '' }}>🏷️ {{ $theme }}</option>
                                @endforeach
                            </select>
                        </form>

                        <a href="{{ route('guru.game.werewolf.template') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            <span>Unduh Template (.xlsx)</span>
                        </a>

                        <button type="button" onclick="openModal('modalImportWerewolf')" class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <span>Import Excel</span>
                        </button>

                        <x-button variant="create" size="sm" type="button" onclick="openModal('modalTambahSoalWerewolf')" class="!shadow-xs !rounded-xl">
                            Pasangan Baru
                        </x-button>
                    </div>
                </div>

                <!-- Tabel Pasangan Kata Kartu -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-4">Kategori / Tema</th>
                                <th class="py-3 px-4">🐑 Kata Domba (Kata A)</th>
                                <th class="py-3 px-4">🐺 Kata Serigala (Kata B)</th>
                                <th class="py-3 px-4">Penjelasan Perbedaan Bahasa Inggris</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            @forelse($daftarSoalWerewolf as $pair)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-[11px] border border-slate-200">
                                            {{ $pair->theme }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="space-y-0.5">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-mono font-extrabold text-emerald-700 text-sm tracking-wide">
                                                    {{ strtoupper($pair->domba_word) }}
                                                </span>
                                                <span class="text-[11px] text-slate-400 font-normal">({{ $pair->domba_meaning }})</span>
                                            </div>
                                            @if(!empty($pair->domba_clues))
                                                <div class="flex flex-wrap gap-1 pt-1">
                                                    @foreach(array_slice($pair->domba_clues, 0, 2) as $clue)
                                                        <span class="px-1.5 py-0.5 rounded bg-emerald-50 text-[10px] text-emerald-800 font-medium">
                                                            • {{ Str::limit($clue, 35) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="space-y-0.5">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-mono font-extrabold text-red-700 text-sm tracking-wide">
                                                    {{ strtoupper($pair->serigala_word) }}
                                                </span>
                                                <span class="text-[11px] text-slate-400 font-normal">({{ $pair->serigala_meaning }})</span>
                                            </div>
                                            @if(!empty($pair->serigala_clues))
                                                <div class="flex flex-wrap gap-1 pt-1">
                                                    @foreach(array_slice($pair->serigala_clues, 0, 2) as $clue)
                                                        <span class="px-1.5 py-0.5 rounded bg-red-50 text-[10px] text-red-800 font-medium">
                                                            • {{ Str::limit($clue, 35) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 max-w-xs">
                                        <p class="text-[11px] text-slate-600 line-clamp-2 leading-relaxed italic">
                                            "{{ $pair->english_difference }}"
                                        </p>
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <form method="POST" action="{{ route('guru.game.werewolf.destroy', $pair->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pasangan kata {{ $pair->domba_word }} vs {{ $pair->serigala_word }}?')">
                                            @csrf
                                            <x-button variant="delete" type="submit" iconOnly size="sm" title="Hapus Pasangan Kata" class="!p-1.5 !rounded-lg" />
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center">
                                        <div class="max-w-sm mx-auto space-y-2">
                                            <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                            </div>
                                            <p class="text-xs font-semibold text-slate-700">Tidak ada pasangan kata ditemukan</p>
                                            <p class="text-[11px] text-slate-400">Silakan gunakan tombol "Pasangan Baru" atau "Import Excel" untuk menambahkan kata kartu.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Accordion & Pagination -->
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                    <div class="flex-1 w-full">
                        @if($daftarSoalWerewolf->total() > 0)
                            <x-pagination :paginator="$daftarSoalWerewolf" />
                        @endif
                    </div>
                    <button type="button" 
                            onclick="toggleBankWerewolf()"
                            class="text-xs text-slate-400 hover:text-slate-600 font-semibold flex items-center gap-1.5 cursor-pointer shrink-0">
                        <span>Tutup Bank Kata</span>
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
                    </button>
                </div>

            </div>

        </div>

    </div>

    <!-- HALAMAN 2 (MAKSIMAL 2 GAME KE BAWAH: ADU LAFAL & SUSUN KALIMAT) -->
    <div id="gamePage2" class="game-page space-y-6 {{ $initialGamePage == 2 ? '' : 'hidden' }}">

        <!-- ========================================== -->
        <!-- GAME 3: Adu Lafal (Pronounce Hero) -->
        <!-- ========================================== -->
        <div class="rounded-2xl border border-slate-200/80 bg-white hover:border-sky-400/40 transition-all overflow-hidden shadow-xs">
            
            <!-- Header Kartu Game Adu Lafal -->
            <div class="p-5 lg:p-6 bg-gradient-to-r from-sky-50/50 via-white to-white flex flex-col sm:flex-row sm:items-center justify-between gap-5 border-b border-slate-100">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-sky-600 text-white flex items-center justify-center shadow-xs shrink-0">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="22"></line>
                        </svg>
                    </div>
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="font-extrabold text-slate-900 text-base tracking-tight">Adu Lafal (Pronounce Hero)</h4>
                            <span class="px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-700 text-[11px] font-bold border border-sky-200">Speaking & Pronunciation</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live di Mobile
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Latihan pelafalan kosakata bahasa Inggris dengan evaluasi kecerdasan buatan (Solo Bot AI) dan adu lafal mabar seru (Multiplayer voting 1–10). Dilengkapi fitur Web Speech Synthesis audio bule asli otomatis.
                        </p>
                        
                        <!-- Chips Metadata Game -->
                        <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px]">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold border border-slate-200/70">
                                <svg class="w-3.5 h-3.5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                Solo (Bot AI) & Mabar (Maks. 5 Player)
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 font-bold border border-sky-200">
                                <svg class="w-3.5 h-3.5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                                Auto-Voice Speech Native (0 KB Storage)
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#EAF5EF] text-[#2E7352] font-bold border border-[#D7EDE1]">
                                <svg class="w-3.5 h-3.5 text-[#2E7352]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                {{ $totalSoalPronounce ?? 0 }} Kosakata Terdaftar
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Kontrol Game Adu Lafal -->
                <div class="flex flex-wrap sm:flex-col gap-2 shrink-0">
                    <x-button variant="create" size="sm" type="button" onclick="openModal('modalTambahPronounceWord')" class="!shadow-xs !rounded-xl !bg-sky-600 hover:!bg-sky-700 !border-sky-600">
                        Tambah Kosakata
                    </x-button>

                    <div class="flex items-center gap-1.5">
                        <button type="button" 
                                onclick="openModal('modalImportPronounce')"
                                title="Import dari Excel (.xlsx / .csv)"
                                class="px-2.5 py-1.5 rounded-xl bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <span>Import</span>
                        </button>
                        <a href="{{ route('guru.game.pronounce.template') }}" 
                           title="Unduh Template Excel (.xlsx)"
                           class="px-2 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 text-xs font-bold transition-all flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        </a>
                    </div>
                    
                    <!-- Tombol Accordion Buka/Tutup Bank Kosakata -->
                    <button type="button"
                            id="btnToggleBankPronounce"
                            onclick="toggleBankPronounce()"
                            class="px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 text-xs font-bold transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                        <span id="textToggleBankPronounce">
                            {{ (request()->has('pronounce_diff') || request()->has('pronounce_page') || request()->get('tab') === 'pronounce') ? 'Tutup Bank Kata' : 'Kelola Bank Kata' }}
                        </span>
                        <svg id="iconToggleBankPronounce" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200 {{ (request()->has('pronounce_diff') || request()->has('pronounce_page') || request()->get('tab') === 'pronounce') ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- PANEL ACCORDION: Bank Kosakata Adu Lafal -->
            <div id="panelBankPronounce" class="{{ (request()->has('pronounce_diff') || request()->has('pronounce_page') || request()->get('tab') === 'pronounce') ? '' : 'hidden' }} p-5 lg:p-6 bg-slate-50/50 border-t border-slate-100 space-y-4">
                
                <!-- Filter & Informasi Ringkas -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-sm">Daftar Kosakata Pelafalan & Audio Native</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Guru dapat menguji lafal bule secara instan dengan menekan tombol speaker 🔊.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Filter Tingkat Kesulitan -->
                        <form method="GET" action="{{ route('guru.game.index') }}" class="flex items-center gap-2">
                            <input type="hidden" name="tab" value="pronounce">
                            @if(!empty($search))
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif
                            <select name="pronounce_diff" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-sky-500">
                                <option value="">📁 Semua Tingkat</option>
                                <option value="mudah" {{ ($pronounceDiffFilter ?? '') === 'mudah' ? 'selected' : '' }}>🟢 Tingkat Mudah</option>
                                <option value="sedang" {{ ($pronounceDiffFilter ?? '') === 'sedang' ? 'selected' : '' }}>🟡 Tingkat Sedang</option>
                                <option value="sulit" {{ ($pronounceDiffFilter ?? '') === 'sulit' ? 'selected' : '' }}>🔴 Tingkat Sulit</option>
                            </select>
                        </form>

                        <a href="{{ route('guru.game.pronounce.template') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            <span>Unduh Template (.xlsx)</span>
                        </a>

                        <button type="button" onclick="openModal('modalImportPronounce')" class="px-3 py-1.5 rounded-xl bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <span>Import Excel</span>
                        </button>
                    </div>
                </div>

                <!-- Tabel Data Kosakata Pelafalan -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-xs">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-600 font-bold">
                                <th class="py-3 px-3.5 text-center w-12">No</th>
                                <th class="py-3 px-4">Kosakata (English Word) & Audio</th>
                                <th class="py-3 px-4">Jenis Kata</th>
                                <th class="py-3 px-4">Arti (Bahasa Indonesia)</th>
                                <th class="py-3 px-3.5 text-center">Tingkat</th>
                                <th class="py-3 px-4">Contoh Kalimat</th>
                                <th class="py-3 px-3.5 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($daftarSoalPronounce as $idx => $item)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-3.5 text-center text-slate-400 font-mono">
                                        {{ $daftarSoalPronounce->firstItem() + $idx }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <div>
                                                <div class="font-extrabold text-slate-900 text-sm tracking-tight">{{ $item->word }}</div>
                                                <div class="text-[11px] text-sky-600 font-mono font-medium">{{ $item->phonetic ?? '-' }}</div>
                                            </div>
                                            <!-- Tombol Dengar Audio Native Bule Seketika -->
                                            <button type="button" 
                                                    onclick="playTeacherWordAudio('{{ addslashes($item->word) }}')" 
                                                    title="Putar Suara Native Bule"
                                                    class="p-1.5 rounded-lg bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200/80 transition-all cursor-pointer">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                            {{ $item->part_of_speech ?? 'Noun (Kata Benda)' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-800">
                                        {{ $item->meaning }}
                                    </td>
                                    <td class="py-3 px-3.5 text-center">
                                        @if($item->difficulty === 'mudah')
                                            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">🟢 Mudah</span>
                                        @elseif($item->difficulty === 'sedang')
                                            <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">🟡 Sedang</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200">🔴 Sulit</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 max-w-xs">
                                        @if(!empty($item->example_sentence))
                                            <div class="flex items-center gap-1.5">
                                                <span class="italic text-slate-700 text-[11px] truncate" title="{{ $item->example_sentence }}">"{{ $item->example_sentence }}"</span>
                                                <button type="button" 
                                                        onclick="playTeacherWordAudio('{{ addslashes($item->example_sentence) }}')"
                                                        title="Dengar Kalimat" 
                                                        class="text-sky-600 hover:text-sky-800 shrink-0">
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Tombol Edit Modal -->
                                            <button type="button" 
                                                    onclick="editPronounceWord({{ json_encode($item) }})"
                                                    title="Edit Kosakata"
                                                    class="p-1.5 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-sky-700 border border-slate-200 transition-colors cursor-pointer">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <form method="POST" action="{{ route('guru.game.pronounce.destroy', $item->id) }}" onsubmit="return confirm('Hapus kosakata {{ addslashes($item->word) }} dari bank soal?')" class="inline-block">
                                                @csrf
                                                <button type="submit" 
                                                        title="Hapus Kosakata"
                                                        class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="w-8 h-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                            <p class="font-semibold text-xs">Belum ada kosakata pelafalan yang terdaftar.</p>
                                            <button type="button" onclick="openModal('modalTambahPronounceWord')" class="text-xs font-bold text-sky-600 hover:underline">
                                                + Tambah Kosakata Pertama Sekarang
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination & Tombol Tutup -->
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                    <div class="flex-1 w-full">
                        @if($daftarSoalPronounce->total() > 0)
                            <x-pagination :paginator="$daftarSoalPronounce" />
                        @endif
                    </div>
                    <button type="button" 
                            onclick="toggleBankPronounce()"
                            class="text-xs text-slate-400 hover:text-slate-600 font-semibold flex items-center gap-1.5 cursor-pointer shrink-0">
                        <span>Tutup Bank Kata</span>
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
                    </button>
                </div>

            </div>

        </div>

        <!-- ========================================== -->
        <!-- GAME 4: Susun Kalimat (Sentence Builder)   -->
        <!-- ========================================== -->
        <div class="rounded-2xl border-2 border-dashed border-indigo-200/90 bg-gradient-to-br from-indigo-50/40 via-white to-purple-50/30 p-5 lg:p-6 transition-all shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-xs shrink-0">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="font-extrabold text-slate-900 text-base tracking-tight">Susun Kalimat (Sentence Builder)</h4>
                            <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-bold border border-indigo-200">Grammar & Syntax</span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 text-[10px] font-bold border border-purple-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span> Game ke-4 (Segera Hadir)
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Permainan balap merangkai kata acak menjadi struktur kalimat bahasa Inggris yang gramatikal. Melatih pemahaman tenses, subjek, predikat, dan objek secara kompetitif & adiktif.
                        </p>
                        
                        <!-- Chips Metadata Game 4 -->
                        <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px]">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white text-indigo-700 font-semibold border border-indigo-200/80 shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                Balap Waktu 45 Detik
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white text-purple-700 font-semibold border border-purple-200/80 shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                4 Tenses Utama
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white text-emerald-700 font-bold border border-emerald-200 shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Target Rilis Berikutnya
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center self-start sm:self-center shrink-0">
                    <span class="px-4 py-2 rounded-xl bg-indigo-100/80 text-indigo-800 text-xs font-extrabold flex items-center gap-1.5 border border-indigo-200">
                        <span>Siap Rilis 🚀</span>
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- KONTROL PAGINATION MODE GAME (MAKSIMAL 2 GAME KE BAWAH PER TAMPILAN) -->
    <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3.5 select-none">
        
        <!-- Info Halaman & Total Game -->
        <div class="text-xs text-slate-500 font-medium flex items-center gap-1.5 order-2 sm:order-1">
            <span class="inline-block w-2 h-2 rounded-full bg-[#4EA87D] shrink-0"></span>
            <span>Menampilkan</span>
            <span id="gamePageItemRange" class="px-2.5 py-0.5 rounded-lg bg-slate-100 font-bold text-slate-800 tracking-tight text-[11px] border border-slate-200/50">
                {{ $initialGamePage == 2 ? 'Halaman 2 (Game 3 & 4)' : 'Halaman 1 (Game 1 & 2)' }}
            </span>
            <span>dari</span>
            <span class="font-bold text-slate-800">4 Mode Permainan</span>
            <span class="text-[11px] text-slate-400 font-normal">(2 game ke bawah per halaman)</span>
        </div>

        <!-- Tombol Navigasi Halaman -->
        <nav role="navigation" aria-label="Navigasi Mode Game" class="flex items-center gap-1 sm:gap-1.5 flex-wrap justify-center order-1 sm:order-2">
            <!-- Tombol Sebelumnya -->
            <button type="button" 
                    id="btnPrevGamePage" 
                    onclick="switchGamePage(1)" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-semibold transition-all select-none {{ $initialGamePage == 1 ? 'bg-slate-50/80 border-slate-200/60 text-slate-300 cursor-not-allowed' : 'bg-white border-slate-200/90 text-slate-700 hover:text-[#4EA87D] hover:border-[#4EA87D]/50 hover:bg-[#F0FDF4] cursor-pointer' }}"
                    {{ $initialGamePage == 1 ? 'disabled' : '' }}>
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                <span>Sebelumnya</span>
            </button>

            <!-- Angka Halaman 1 -->
            <button type="button" 
                    id="btnPageNum1" 
                    onclick="switchGamePage(1)" 
                    class="w-8 h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer {{ $initialGamePage == 1 ? 'font-bold bg-[#2E7352] text-white shadow-xs' : 'font-semibold bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-[#2E7352]' }}">
                1
            </button>

            <!-- Angka Halaman 2 -->
            <button type="button" 
                    id="btnPageNum2" 
                    onclick="switchGamePage(2)" 
                    class="w-8 h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer {{ $initialGamePage == 2 ? 'font-bold bg-[#2E7352] text-white shadow-xs' : 'font-semibold bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-[#2E7352]' }}">
                2
            </button>

            <!-- Tombol Selanjutnya -->
            <button type="button" 
                    id="btnNextGamePage" 
                    onclick="switchGamePage(2)" 
                    class="group inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-semibold shadow-xs hover:shadow-sm transition-all select-none {{ $initialGamePage == 2 ? 'bg-slate-50/80 border-slate-200/60 text-slate-300 cursor-not-allowed' : 'bg-white border-slate-200/90 text-slate-700 hover:text-[#4EA87D] hover:border-[#4EA87D]/50 hover:bg-[#F0FDF4] cursor-pointer' }}"
                    {{ $initialGamePage == 2 ? 'disabled' : '' }}>
                <span>Selanjutnya</span>
                <svg class="w-3.5 h-3.5 shrink-0 text-slate-500 group-hover:text-[#4EA87D] group-hover:translate-x-0.5 transition-all" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </nav>
    </div>

</div>

<!-- Script Toggle Accordion & Pagination Game -->
<script>
    let currentGamePage = {{ $initialGamePage }};
    const totalGamePages = 2;

    function switchGamePage(page) {
        if (page < 1 || page > totalGamePages) return;
        currentGamePage = page;

        const p1 = document.getElementById('gamePage1');
        const p2 = document.getElementById('gamePage2');
        if (p1 && p2) {
            if (page === 1) {
                p1.classList.remove('hidden');
                p2.classList.add('hidden');
            } else {
                p1.classList.add('hidden');
                p2.classList.remove('hidden');
            }
        }

        const rangeEl = document.getElementById('gamePageItemRange');
        if (rangeEl) {
            rangeEl.innerText = (page === 1) ? 'Halaman 1 (Game 1 & 2)' : 'Halaman 2 (Game 3 & 4)';
        }

        const btnPrev = document.getElementById('btnPrevGamePage');
        const btnNext = document.getElementById('btnNextGamePage');
        const btn1 = document.getElementById('btnPageNum1');
        const btn2 = document.getElementById('btnPageNum2');

        if (btn1 && btn2) {
            if (page === 1) {
                btn1.className = 'w-8 h-8 rounded-xl font-bold text-xs flex items-center justify-center transition-all cursor-pointer bg-[#2E7352] text-white shadow-xs';
                btn2.className = 'w-8 h-8 rounded-xl font-semibold text-xs flex items-center justify-center transition-all cursor-pointer bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-[#2E7352]';
            } else {
                btn2.className = 'w-8 h-8 rounded-xl font-bold text-xs flex items-center justify-center transition-all cursor-pointer bg-[#2E7352] text-white shadow-xs';
                btn1.className = 'w-8 h-8 rounded-xl font-semibold text-xs flex items-center justify-center transition-all cursor-pointer bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 hover:text-[#2E7352]';
            }
        }

        if (btnPrev) {
            if (page === 1) {
                btnPrev.disabled = true;
                btnPrev.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50/80 border border-slate-200/60 text-slate-300 text-xs font-semibold cursor-not-allowed select-none';
            } else {
                btnPrev.disabled = false;
                btnPrev.className = 'group inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#4EA87D] hover:border-[#4EA87D]/50 hover:bg-[#F0FDF4] text-xs font-semibold shadow-xs hover:shadow-sm transition-all cursor-pointer select-none';
            }
        }

        if (btnNext) {
            if (page === totalGamePages) {
                btnNext.disabled = true;
                btnNext.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50/80 border border-slate-200/60 text-slate-300 text-xs font-semibold cursor-not-allowed select-none';
            } else {
                btnNext.disabled = false;
                btnNext.className = 'group inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#4EA87D] hover:border-[#4EA87D]/50 hover:bg-[#F0FDF4] text-xs font-semibold shadow-xs hover:shadow-sm transition-all cursor-pointer select-none';
            }
        }

        try {
            const url = new URL(window.location);
            url.searchParams.set('game_page', page);
            if (page === 2) {
                url.searchParams.set('tab', 'pronounce');
            } else {
                url.searchParams.delete('tab');
            }
            window.history.replaceState({}, '', url);
        } catch(e) {}
    }
    function toggleBankSoal() {
        const panel = document.getElementById('panelBankSoalIkan');
        const text = document.getElementById('textToggleBankSoal');
        const icon = document.getElementById('iconToggleBankSoal');

        if (panel.classList.contains('hidden')) {
            panel.classList.remove('hidden');
            text.innerText = 'Tutup Bank Soal';
            icon.classList.add('rotate-180');
        } else {
            panel.classList.add('hidden');
            text.innerText = 'Kelola Bank Soal';
            icon.classList.remove('rotate-180');
        }
    }

    function toggleBankWerewolf() {
        const panel = document.getElementById('panelBankWerewolf');
        const text = document.getElementById('textToggleBankWerewolf');
        const icon = document.getElementById('iconToggleBankWerewolf');

        if (panel.classList.contains('hidden')) {
            panel.classList.remove('hidden');
            text.innerText = 'Tutup Bank Kata';
            icon.classList.add('rotate-180');
        } else {
            panel.classList.add('hidden');
            text.innerText = 'Kelola Bank Kata';
            icon.classList.remove('rotate-180');
        }
    }

    function toggleBankPronounce() {
        const panel = document.getElementById('panelBankPronounce');
        const text = document.getElementById('textToggleBankPronounce');
        const icon = document.getElementById('iconToggleBankPronounce');

        if (panel.classList.contains('hidden')) {
            panel.classList.remove('hidden');
            text.innerText = 'Tutup Bank Kata';
            icon.classList.add('rotate-180');
        } else {
            panel.classList.add('hidden');
            text.innerText = 'Kelola Bank Kata';
            icon.classList.remove('rotate-180');
        }
    }
</script>
