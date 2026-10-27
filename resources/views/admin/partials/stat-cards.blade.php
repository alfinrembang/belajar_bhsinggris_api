<!-- Kartu Statistik Ringkasan (Enterprise Metric Cards) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
    
    <!-- Kartu 1: Total Guru -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] hover:border-[#D7EDE1] transition-all flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Guru Pengajar</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1 tracking-tight">{{ $totalGuru ?? 0 }}</h3>
            <p class="text-[11px] text-[#2E7352] font-medium mt-1 flex items-center gap-1">
                <span>Aktif di Portal Guru</span>
            </p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
    </div>

    <!-- Kartu 2: Total Siswa -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] hover:border-slate-300 transition-all flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Siswa Terdaftar</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1 tracking-tight">{{ $totalSiswa ?? 0 }}</h3>
            <p class="text-[11px] text-emerald-600 font-medium mt-1 flex items-center gap-1">
                <span>Terhubung di Aplikasi Mobile</span>
            </p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-slate-50 border border-slate-100 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
            </svg>
        </div>
    </div>

    <!-- Kartu 3: Total Materi Pembelajaran -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] hover:border-slate-300 transition-all flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Modul Pembelajaran</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1 tracking-tight">{{ $totalMateri ?? 0 }}</h3>
            <p class="text-[11px] text-slate-500 font-medium mt-1">
                <span>Bahan Ajar & Listening</span>
            </p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
        </div>
    </div>

    <!-- Kartu 4: Administrator Sistem -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] hover:border-slate-300 transition-all flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Super Administrator</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1 tracking-tight">{{ $totalAdmin ?? 1 }}</h3>
            <p class="text-[11px] text-slate-500 font-medium mt-1">
                <span>Otoritas Penuh Sekolah</span>
            </p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
        </div>
    </div>

</div>
