<!-- Header Banner Pendidik (Signature Royal Blue) -->
<div class="bg-gradient-to-r from-[#1E6BFF] via-[#155BE5] to-[#0A56E2] rounded-3xl p-6 lg:p-7 text-white shadow-[0_10px_30px_rgba(30,107,255,0.22)] relative overflow-hidden">
    <!-- Ambient Glow Dekoratif -->
    <div class="absolute -right-16 -top-16 w-72 h-72 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -bottom-20 w-56 h-56 rounded-full bg-blue-400/20 blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="max-w-xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-white text-xs font-medium border border-white/20 mb-3 backdrop-blur-xs">
                <svg class="w-3.5 h-3.5 text-blue-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                </svg>
                <span>Portal Guru & Pendidik Bahasa Inggris</span>
                @if(isset($pengaturanAkademik) && $pengaturanAkademik->tahun_ajaran)
                    <span class="w-1 h-1 rounded-full bg-white/50"></span>
                    <span class="text-blue-100 font-semibold">T.A. {{ $pengaturanAkademik->tahun_ajaran }}</span>
                @endif
            </div>

            <h2 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-white">
                Selamat Datang, {{ auth()->user()->name ?? 'Bapak/Ibu Guru' }}
            </h2>
            <p class="text-blue-100/90 text-xs sm:text-sm mt-1.5 leading-relaxed font-normal">
                Pantau perkembangan aktivitas belajar siswa, evaluasi butir kuis, dan kelola modul ajar bahasa Inggris yang terintegrasi langsung ke aplikasi mobile siswa.
            </p>
        </div>

        <!-- Tombol Pintasan Cepat Header -->
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('guru.materi.create') }}" 
               class="px-4 py-2.5 rounded-xl bg-white hover:bg-blue-50 text-[#1E6BFF] font-bold text-xs shadow-sm transition-all inline-flex items-center gap-2 active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 text-[#1E6BFF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Materi</span>
            </a>
            <a href="{{ route('guru.quiz.create') }}" 
               class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-semibold text-xs border border-white/25 transition-all inline-flex items-center gap-2 active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                    <path d="M9 9h6"></path>
                    <path d="M9 13h6"></path>
                    <path d="M9 17h4"></path>
                </svg>
                <span>Buat Kuis</span>
            </a>
        </div>
    </div>
</div>
