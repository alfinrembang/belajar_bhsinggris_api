@props([
    'active' => 'dashboard'
])

<aside class="w-72 lg:w-80 bg-white border-r border-slate-100 flex flex-col justify-between shrink-0 h-screen sticky top-0 overflow-y-auto select-none p-5 lg:p-6 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
    <!-- Bagian Atas: Brand & Navigasi -->
    <div>
        <!-- 1. Header Brand (Logo, Nama, Sub-title) -->
        <div class="flex items-center gap-3.5 mb-3.5">
            <!-- Ikon Brand Topi Sarjana Biru Gradien -->
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#1E6BFF] to-[#0A56E2] flex items-center justify-center shadow-[0_6px_16px_rgba(30,107,255,0.28)] shrink-0">
                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18C5 19.94 8.13 22 12 22C15.87 22 19 19.94 19 17.18V13.18L12 17L5 13.18Z" />
                </svg>
            </div>
            <div class="min-w-0">
                <h1 class="text-[17px] font-bold text-slate-800 leading-tight tracking-tight truncate">Study English Prima</h1>
                <p class="text-[11px] font-bold text-[#1E6BFF] tracking-wider uppercase mt-0.5">GURU PORTAL</p>
            </div>
        </div>

        <!-- 2. Badge Status: Portal Pengajar & Administrator -->
        <div class="mb-7">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#EBF2FE] text-[#1E6BFF] text-[11px] font-semibold">
                <svg class="w-3.5 h-3.5 text-[#1E6BFF] shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                </svg>
                Portal Pengajar & Administrator
            </span>
        </div>

        <!-- 3. Section Label: NAVIGASI UTAMA -->
        <p class="text-[11px] font-bold text-slate-400 tracking-wider uppercase mb-2.5 px-2">NAVIGASI UTAMA</p>

        <!-- 4. Daftar Menu Navigasi -->
        <nav class="space-y-1.5">
            <!-- 1. Dashboard Utama -->
            <a href="{{ route('guru.dashboard') }}" 
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[14px] transition-all duration-150 {{ $active === 'dashboard' ? 'bg-[#1E6BFF] text-white shadow-[0_6px_16px_rgba(30,107,255,0.28)] font-semibold' : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 font-medium' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active === 'dashboard' ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                </svg>
                <span>Dashboard Utama</span>
            </a>

            <!-- 2. Manajemen Materi -->
            <a href="{{ route('guru.materi.index') }}" 
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[14px] transition-all duration-150 {{ $active === 'materi' ? 'bg-[#1E6BFF] text-white shadow-[0_6px_16px_rgba(30,107,255,0.28)] font-semibold' : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 font-medium' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active === 'materi' ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <span>Manajemen Materi</span>
            </a>

            <!-- 2b. Kategori Materi -->
            <a href="{{ route('guru.kategori.index') }}" 
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[14px] transition-all duration-150 {{ $active === 'kategori' ? 'bg-[#1E6BFF] text-white shadow-[0_6px_16px_rgba(30,107,255,0.28)] font-semibold' : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 font-medium' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active === 'kategori' ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
                <span>Kategori Materi</span>
            </a>

            <!-- 3. Modul Listening -->
            <a href="{{ route('guru.listening.index') }}" 
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[14px] transition-all duration-150 {{ $active === 'listening' ? 'bg-[#1E6BFF] text-white shadow-[0_6px_16px_rgba(30,107,255,0.28)] font-semibold' : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 font-medium' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active === 'listening' ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                </svg>
                <span>Modul Listening</span>
            </a>

            <!-- 4. Bank Soal & Kuis -->
            <a href="{{ route('guru.quiz.index') }}" 
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[14px] transition-all duration-150 {{ $active === 'quiz' ? 'bg-[#1E6BFF] text-white shadow-[0_6px_16px_rgba(30,107,255,0.28)] font-semibold' : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 font-medium' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active === 'quiz' ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                    <path d="M9 9h6"></path>
                    <path d="M9 13h6"></path>
                    <path d="M9 17h4"></path>
                    <path d="M12 9v1a1.5 1.5 0 0 0 1.5 1.5"></path>
                </svg>
                <span>Bank Soal & Kuis</span>
            </a>

            <!-- 5. Bank Kosakata -->
            <a href="{{ route('guru.kosakata.index') }}" 
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[14px] transition-all duration-150 {{ $active === 'kosakata' ? 'bg-[#1E6BFF] text-white shadow-[0_6px_16px_rgba(30,107,255,0.28)] font-semibold' : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 font-medium' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active === 'kosakata' ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path>
                    <path d="M9 7h6"></path>
                    <path d="M9 11h6"></path>
                </svg>
                <span>Bank Kosakata</span>
            </a>

            <!-- 6. Manajemen Siswa & Kelas -->
            <a href="{{ route('guru.siswa.index') }}" 
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[14px] transition-all duration-150 {{ $active === 'siswa' ? 'bg-[#1E6BFF] text-white shadow-[0_6px_16px_rgba(30,107,255,0.28)] font-semibold' : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 font-medium' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active === 'siswa' ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>Manajemen Siswa & Kelas</span>
            </a>

            <!-- 7. Mini Games & Leaderboard -->
            <a href="{{ route('guru.game.index') }}" 
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[14px] transition-all duration-150 {{ $active === 'game' ? 'bg-[#1E6BFF] text-white shadow-[0_6px_16px_rgba(30,107,255,0.28)] font-semibold' : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 font-medium' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active === 'game' ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="6" width="20" height="12" rx="4"></rect>
                    <path d="M6 12h4m-2-2v4"></path>
                    <circle cx="17" cy="10" r="1" fill="currentColor"></circle>
                    <circle cx="15" cy="13" r="1" fill="currentColor"></circle>
                </svg>
                <span>Mini Games & Leaderboard</span>
            </a>

            <!-- 8. Laporan & Analitik -->
            <a href="{{ route('guru.laporan.index') }}" 
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[14px] transition-all duration-150 {{ $active === 'laporan' ? 'bg-[#1E6BFF] text-white shadow-[0_6px_16px_rgba(30,107,255,0.28)] font-semibold' : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 font-medium' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active === 'laporan' ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"></path>
                    <path d="M7 16l4-6 4 3 6-7"></path>
                </svg>
                <span>Laporan & Analitik</span>
            </a>

            <!-- 9. Pengaturan Sistem -->
            <a href="{{ route('guru.pengaturan.index') }}" 
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[14px] transition-all duration-150 {{ $active === 'pengaturan' ? 'bg-[#1E6BFF] text-white shadow-[0_6px_16px_rgba(30,107,255,0.28)] font-semibold' : 'text-slate-700 hover:bg-slate-100/80 hover:text-slate-900 font-medium' }}">
                <svg class="w-5 h-5 shrink-0 {{ $active === 'pengaturan' ? 'text-white' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06-.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                <span>Pengaturan Sistem</span>
            </a>
        </nav>
    </div>

    <!-- Bagian Bawah: Profil Pengguna & Logout -->
    <div class="pt-5 border-t border-slate-100 mt-6">
        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100/80">
            <div class="flex items-center gap-3 min-w-0">
                <!-- Avatar Avatar Default -->
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#1E6BFF] flex items-center justify-center font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-800 truncate leading-tight">{{ auth()->user()->name ?? 'Guru Pengajar' }}</p>
                    <p class="text-[11px] text-slate-400 capitalize truncate mt-0.5">{{ auth()->user()->role ?? 'guru' }}</p>
                </div>
            </div>

            <!-- Tombol Keluar (Logout) -->
            <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" 
                        title="Keluar dari sesi"
                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
