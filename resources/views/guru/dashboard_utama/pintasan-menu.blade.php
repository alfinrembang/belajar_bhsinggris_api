<!-- Hub Pintasan Navigasi Menu Guru -->
<div class="bg-white rounded-3xl p-6 lg:p-7 border border-slate-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.02)] space-y-4">
    
    <!-- Header Kartu -->
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base">Pintasan Akses Menu</h3>
                <p class="text-xs text-slate-400">Navigasi cepat ke seluruh modul portal pendidik</p>
            </div>
        </div>
    </div>

    <!-- Grid Menu Shortcuts -->
    <div class="grid grid-cols-2 gap-2.5">
        
        <!-- 1. Manajemen Siswa -->
        <a href="{{ route('guru.siswa.index') }}" 
           class="p-3 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-blue-50/70 hover:border-blue-200 transition-all flex items-center gap-3 group cursor-pointer">
            <div class="w-8 h-8 rounded-xl bg-blue-100 text-[#1E6BFF] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 group-hover:text-[#1E6BFF] transition-colors truncate">Data Siswa</p>
                <p class="text-[10px] text-slate-400 truncate">Rombel & Akun</p>
            </div>
        </a>

        <!-- 2. Materi Ajar -->
        <a href="{{ route('guru.materi.index') }}" 
           class="p-3 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-blue-50/70 hover:border-blue-200 transition-all flex items-center gap-3 group cursor-pointer">
            <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 group-hover:text-[#1E6BFF] transition-colors truncate">Modul Materi</p>
                <p class="text-[10px] text-slate-400 truncate">Teori & Bacaan</p>
            </div>
        </a>

        <!-- 3. Modul Listening -->
        <a href="{{ route('guru.listening.index') }}" 
           class="p-3 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-purple-50/70 hover:border-purple-200 transition-all flex items-center gap-3 group cursor-pointer">
            <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 group-hover:text-purple-600 transition-colors truncate">Listening</p>
                <p class="text-[10px] text-slate-400 truncate">Audio Percakapan</p>
            </div>
        </a>

        <!-- 4. Paket Kuis -->
        <a href="{{ route('guru.quiz.index') }}" 
           class="p-3 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-emerald-50/70 hover:border-emerald-200 transition-all flex items-center gap-3 group cursor-pointer">
            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                    <path d="M9 9h6"></path>
                    <path d="M9 13h6"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 group-hover:text-emerald-600 transition-colors truncate">Bank Kuis</p>
                <p class="text-[10px] text-slate-400 truncate">Evaluasi & Soal</p>
            </div>
        </a>

        <!-- 5. Laporan & Analitik -->
        <a href="{{ route('guru.laporan.index') }}" 
           class="p-3 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-amber-50/70 hover:border-amber-200 transition-all flex items-center gap-3 group cursor-pointer">
            <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 group-hover:text-amber-700 transition-colors truncate">Laporan Nilai</p>
                <p class="text-[10px] text-slate-400 truncate">Leger & Rapor</p>
            </div>
        </a>

        <!-- 6. Pengaturan Sistem -->
        <a href="{{ route('guru.pengaturan.index') }}" 
           class="p-3 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-slate-100 hover:border-slate-300 transition-all flex items-center gap-3 group cursor-pointer">
            <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-700 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 group-hover:text-slate-900 transition-colors truncate">Pengaturan</p>
                <p class="text-[10px] text-slate-400 truncate">KKM & Tahun Ajaran</p>
            </div>
        </a>

    </div>

</div>
