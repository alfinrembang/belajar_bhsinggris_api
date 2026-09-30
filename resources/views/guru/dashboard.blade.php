<x-layout 
    title="Dashboard Guru - Study English Prima" 
    pageTitle="Dashboard Utama" 
    pageSubtitle="Ringkasan aktivitas belajar, performa kelas, dan manajemen materi." 
    active="dashboard"
>
    <!-- Banner Sambutan -->
    <div class="bg-gradient-to-r from-[#1E6BFF] to-[#0A56E2] rounded-3xl p-6 lg:p-8 text-white relative overflow-hidden shadow-[0_10px_30px_rgba(30,107,255,0.25)]">
        <!-- Dekorasi Background Bulatan Halus -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="absolute right-32 -top-12 w-48 h-48 rounded-full bg-blue-300/20 blur-xl pointer-events-none"></div>

        <div class="relative z-10 max-w-xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-white text-xs font-medium mb-3 border border-white/20">
                👋 Selamat Datang Kembali
            </span>
            <h2 class="text-2xl lg:text-3xl font-bold mb-2 tracking-tight">Halo, {{ auth()->user()->name ?? 'Guru' }}!</h2>
            <p class="text-white/80 text-xs lg:text-sm leading-relaxed mb-5">
                Kelola pembelajaran siswa dengan mudah. Seluruh modul materi, kuis, listening, dan bank kosakata yang diatur di sini akan langsung tampil pada aplikasi mobile siswa.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('guru.materi.index') }}" class="px-5 py-2.5 rounded-xl bg-white text-[#1E6BFF] font-semibold text-xs hover:bg-blue-50 transition-all shadow-sm">
                    + Tambah Materi Baru
                </a>
                <a href="{{ route('guru.quiz.index') }}" class="px-5 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-semibold text-xs border border-white/20 transition-all">
                    Buat Paket Kuis
                </a>
            </div>
        </div>
    </div>

    <!-- Kartu Statistik Ringkasan (Overview Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
        
        <!-- Kartu 1: Total Siswa -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalSiswa ?? 4 }}</h3>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                    <span>↑ Aktif terdaftar</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>

        <!-- Kartu 2: Bank Kosakata -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Bank Kosakata</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalKosakata ?? 10 }}</h3>
                <p class="text-[11px] text-blue-600 font-semibold mt-1">Kosakata aktif</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path>
                    <path d="M9 7h6"></path>
                    <path d="M9 11h6"></path>
                </svg>
            </div>
        </div>

        <!-- Kartu 3: Modul Materi -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Modul Materi</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">8</h3>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">4 Kategori materi</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
            </div>
        </div>

        <!-- Kartu 4: Soal & Kuis -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Bank Soal Kuis</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">24</h3>
                <p class="text-[11px] text-purple-600 font-semibold mt-1">Siap dikerjakan siswa</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                    <path d="M9 9h6"></path>
                    <path d="M9 13h6"></path>
                    <path d="M9 17h4"></path>
                </svg>
            </div>
        </div>

    </div>
</x-layout>
