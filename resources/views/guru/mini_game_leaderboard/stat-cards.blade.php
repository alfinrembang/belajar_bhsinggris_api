<!-- Bar Aksi Atas: Pencarian, Filter & Tombol Tambah Soal -->
<div class="bg-white p-4 lg:p-5 rounded-3xl border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.02)] flex flex-col md:flex-row md:items-center justify-between gap-4">
    <!-- Form Pencarian dengan Indikator Reset -->
    <div class="flex items-center gap-3 w-full md:w-auto">
        <form method="GET" action="{{ route('guru.game.index') }}" class="relative w-full sm:w-80 md:w-96">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" 
                   name="q"
                   value="{{ $search ?? '' }}"
                   autocomplete="off"
                   autocorrect="off"
                   spellcheck="false"
                   placeholder="Cari kosakata, arti, atau siswa..." 
                   class="w-full pl-10 pr-9 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/90 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
            @if(!empty($search))
                <a href="{{ route('guru.game.index') }}" 
                   title="Hapus pencarian"
                   class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">
                    &times;
                </a>
            @endif
        </form>

        @if(!empty($search))
            <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-200/70 whitespace-nowrap">
                <span>Filter:</span>
                <span class="font-bold">"{{ $search }}"</span>
            </span>
        @endif
    </div>

    <!-- Aksi Cepat Guru: Tambah Soal / Import -->
    <div class="flex flex-wrap items-center gap-2 shrink-0">
        <button type="button" 
                onclick="openModal('modalImportFishing')"
                class="px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            <span>Import Excel</span>
        </button>
        <x-button variant="create" type="button" onclick="openModal('modalTambahSoalIkan')" class="!shadow-[0_4px_14px_rgba(78,168,125,0.22)]">
            Tambah Soal Kosakata
        </x-button>
    </div>
</div>

<!-- Ringkasan Statistik Metrik Game -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
    
    <!-- Kartu 1: Modul Engine Game Mobile -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.02)] flex items-start justify-between gap-4">
        <div class="space-y-1.5 min-w-0">
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Modul Game Mobile</p>
            <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">2 Engine</h3>
            <div class="flex flex-wrap items-center gap-1.5 pt-1 text-[11px]">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#EAF5EF] text-[#2E7352] font-semibold border border-[#D7EDE1]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#4EA87D]"></span>
                    Word Fishing ({{ $totalSoalIkan }})
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-semibold border border-indigo-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    Wolf vs Sheep ({{ $totalSoalWerewolf ?? 0 }})
                </span>
            </div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0 shadow-xs">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                <path d="M6 12h4m-2-2v4"></path>
                <line x1="15" y1="11" x2="15" y2="11.01"></line>
                <line x1="18" y1="13" x2="18" y2="13.01"></line>
            </svg>
        </div>
    </div>

    <!-- Kartu 2: Top Rank Leaderboard Siswa -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.02)] flex items-start justify-between gap-4">
        <div class="space-y-1.5 min-w-0">
            <p class="text-[11px] font-bold text-amber-700/80 uppercase tracking-wider flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                Peringkat #1 Teratas
            </p>
            <h3 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight truncate">
                {{ $topPlayer['nama'] ?? 'Belum ada data' }}
            </h3>
            <div class="flex items-center gap-2 pt-1 text-[11px] font-semibold">
                <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200/70 font-mono">
                    {{ number_format($topPlayer['total_skor'] ?? 0) }} PTS
                </span>
                <span class="text-slate-400 font-normal truncate">
                    {{ $topPlayer['kelas'] ?? '' }}
                </span>
            </div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0 shadow-xs">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="7"></circle>
                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
            </svg>
        </div>
    </div>

    <!-- Kartu 3: Total Bank Kosakata & Siswa Aktif -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.02)] flex items-start justify-between gap-4">
        <div class="space-y-1.5 min-w-0">
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Bank Kosakata</p>
            <h3 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $totalSoalIkan + ($totalSoalWerewolf ?? 0) }} <span class="text-sm font-semibold text-slate-500">Butir</span>
            </h3>
            <div class="flex items-center gap-1.5 pt-1 text-[11px] text-slate-500 font-medium">
                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold">
                    {{ $totalPesertaAktif }} Siswa Aktif
                </span>
                <span class="text-slate-400">• Real-Time Sync</span>
            </div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-sky-50 border border-sky-200 text-sky-700 flex items-center justify-center shrink-0 shadow-xs">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                <line x1="9" y1="7" x2="15" y2="7"></line>
                <line x1="9" y1="11" x2="13" y2="11"></line>
            </svg>
        </div>
    </div>

</div>
