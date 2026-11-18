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
                <p class="text-xs text-slate-400 mt-0.5">Kontrol konten soal dan parameter permainan pada aplikasi mobile Flutter.</p>
            </div>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] text-xs font-bold self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-[#4EA87D] animate-pulse"></span>
            2 Mode Game Aktif
        </span>
    </div>

    <div class="space-y-5">
        
        <!-- GAME 1: Tangkap Ikan (Word Fishing) dengan Panel Bank Soal Collapsible -->
        <div class="rounded-2xl border border-slate-200/80 bg-white hover:border-sky-300 transition-all overflow-hidden shadow-xs">
            
            <!-- Header Kartu Game Tangkap Ikan -->
            <div class="p-5 lg:p-6 bg-gradient-to-r from-sky-50/50 via-white to-white flex flex-col sm:flex-row sm:items-center justify-between gap-5 border-b border-slate-100">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-sky-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(2,132,199,0.25)] shrink-0">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 12c-2.5 0-4-2.5-4-5 3.5 0 6 2 8 5"></path>
                            <path d="M18 12c2.5 0 4-2.5 4-5-3.5 0-6 2-8 5"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                        </svg>
                    </div>
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="font-extrabold text-slate-900 text-base tracking-tight">Tangkap Ikan (Word Fishing)</h4>
                            <span class="px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 text-[11px] font-bold">Vocabulary & Grammar</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live di Mobile
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Latihan kosakata interaktif berbasis akuarium 3D. Siswa mengidentifikasi arti kata dan kategori <span class="font-semibold text-slate-700">Part of Speech</span> dalam batas waktu 60 detik untuk memperoleh skor serta koin reward.
                        </p>
                        
                        <!-- Chips Metadata Game -->
                        <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px]">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold border border-slate-200/70">
                                <svg class="w-3.5 h-3.5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                9 Level Akuarium
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold border border-slate-200/70">
                                <svg class="w-3.5 h-3.5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                Timer 60 Detik
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-50 text-sky-800 font-bold border border-sky-200/70">
                                <svg class="w-3.5 h-3.5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                {{ $totalSoalIkan }} Soal Terdaftar
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Kontrol Game Tangkap Ikan -->
                <div class="flex sm:flex-col gap-2 shrink-0">
                    <x-button variant="create" size="sm" type="button" onclick="openModal('modalTambahSoalIkan')" class="!bg-sky-600 hover:!bg-sky-700 !shadow-xs !rounded-xl">
                        + Tambah Soal
                    </x-button>
                    
                    <!-- Tombol Accordion Buka/Tutup Bank Soal -->
                    <button type="button"
                            id="btnToggleBankSoal"
                            onclick="toggleBankSoal()"
                            class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 text-xs font-bold transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                        <span id="textToggleBankSoal">
                            {{ (request()->has('level') || request()->has('page') || session('success') || $errors->any()) ? 'Tutup Bank Soal' : 'Kelola Bank Soal' }}
                        </span>
                        <svg id="iconToggleBankSoal" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200 {{ (request()->has('level') || request()->has('page') || session('success') || $errors->any()) ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- PANEL ACCORDION: Bank Soal Tangkap Ikan -->
            <div id="panelBankSoalIkan" class="{{ (request()->has('level') || request()->has('page') || session('success') || $errors->any()) ? '' : 'hidden' }} bg-white p-5 lg:p-6 transition-all">
                
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

                    <div class="flex items-center gap-2.5">
                        <!-- Filter Level Dropdown -->
                        <form method="GET" action="{{ route('guru.game.index') }}" class="flex items-center gap-2">
                            @if(!empty($search))
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif
                            <select name="level" 
                                    onchange="this.form.submit()"
                                    class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:bg-white focus:border-[#4EA87D] cursor-pointer">
                                <option value="">Semua Level (1-9)</option>
                                @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}" {{ ($levelFilter ?? '') == $i ? 'selected' : '' }}>Level {{ $i }}</option>
                                @endfor
                            </select>
                        </form>

                        <x-button variant="create" size="sm" type="button" onclick="openModal('modalTambahSoalIkan')" class="!rounded-xl">
                            + Soal Baru
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
                                            <p class="text-[11px] text-slate-400">Silakan sesuaikan filter level atau klik tombol "+ Soal Baru" untuk menambahkan kosakata.</p>
                                        </div>
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
                            class="text-xs text-slate-400 hover:text-slate-600 font-semibold flex items-center gap-1.5 cursor-pointer">
                        <span>Tutup Bank Soal</span>
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
                    </button>
                </div>

            </div>

        </div>

        <!-- GAME 2: Tebak Serigala (Wolf vs Sheep) -->
        <div class="rounded-2xl border border-slate-200/80 bg-white hover:border-indigo-300 transition-all overflow-hidden p-5 lg:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(79,70,229,0.25)] shrink-0">
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
                        <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 text-[11px] font-bold">Speaking & Deduction</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Room Mabar & AI Bot
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Permainan deduksi sosial interaktif 5 pemain (4 Domba vs 1 Serigala). Melatih kemampuan siswa dalam merangkai <span class="font-semibold text-slate-700">Descriptive Clues</span> dalam bahasa Inggris serta mengungkap serigala yang menyamar lewat voting musyawarah.
                    </p>
                    
                    <!-- Chips Metadata Game -->
                    <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px]">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold border border-slate-200/70">
                            <svg class="w-3.5 h-3.5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                            Format: 4 Domba vs 1 Serigala
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold border border-slate-200/70">
                            <svg class="w-3.5 h-3.5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Room Kode 6-Digit & AI Bot
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-800 font-bold border border-indigo-200/70">
                            <svg class="w-3.5 h-3.5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            15+ Pasangan Kata Kontras
                        </span>
                    </div>
                </div>
            </div>

            <!-- Status Indicator Kanan -->
            <div class="shrink-0 self-start sm:self-center">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Aktif di Flutter Siswa
                </span>
            </div>
        </div>

    </div>

</div>
