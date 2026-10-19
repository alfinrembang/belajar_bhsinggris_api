<!-- Header Eksekutif Administrator (Signature Royal Blue) -->
<div class="bg-gradient-to-r from-[#1E6BFF] via-[#155BE5] to-[#0A56E2] rounded-2xl p-5 lg:p-6 text-white shadow-[0_10px_25px_rgba(30,107,255,0.20)] relative overflow-hidden">
    <!-- Subtle Ambient Glow -->
    <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="max-w-xl">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white/15 text-white text-[11px] font-medium border border-white/20 mb-2.5">
                <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <path d="m9 12 2 2 4-4"></path>
                </svg>
                <span>Administrator Control Center</span>
            </div>
            <h2 class="text-xl lg:text-2xl font-bold tracking-tight text-white">
                Selamat Datang, {{ auth()->user()->name ?? 'Administrator' }}
            </h2>
            <p class="text-blue-100 text-xs mt-1 leading-relaxed">
                Pusat kendali otorisasi guru, penetapan rombel kelas binaan, dan otoritas tahun akademik sekolah.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <button type="button" 
                    onclick="openTambahGuruModal()" 
                    class="px-4 py-2.5 rounded-xl bg-white hover:bg-blue-50 text-[#1E6BFF] font-semibold text-xs shadow-xs transition-all inline-flex items-center gap-2 cursor-pointer active:scale-95">
                <svg class="w-4 h-4 text-[#1E6BFF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Akun Guru</span>
            </button>
            <a href="{{ route('guru.siswa.index') }}" 
               class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-semibold text-xs border border-white/20 transition-all inline-flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>Data Siswa Sekolah</span>
            </a>
        </div>
    </div>
</div>
