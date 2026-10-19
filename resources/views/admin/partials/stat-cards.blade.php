<!-- Kartu Statistik Ringkasan (Overview Cards) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
    
    <!-- Kartu 1: Total Guru -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Akun Guru</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalGuru ?? 0 }}</h3>
            <p class="text-[11px] text-[#1E6BFF] font-semibold mt-1 flex items-center gap-1">
                <span>Tenaga Pengajar Aktif</span>
            </p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
    </div>

    <!-- Kartu 2: Total Siswa -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Siswa</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalSiswa ?? 0 }}</h3>
            <p class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                <span>Terdaftar di Aplikasi Mobile</span>
            </p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
            </svg>
        </div>
    </div>

    <!-- Kartu 3: Total Materi Pembelajaran -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Modul Materi</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalMateri ?? 0 }}</h3>
            <p class="text-[11px] text-amber-600 font-semibold mt-1">Bahan Ajar Tersedia</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
        </div>
    </div>

    <!-- Kartu 4: Administrator Sistem -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Akun Admin</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalAdmin ?? 1 }}</h3>
            <p class="text-[11px] text-indigo-600 font-semibold mt-1">Hak Akses Super Admin</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
        </div>
    </div>

</div>
