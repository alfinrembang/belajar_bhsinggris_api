<!-- Banner Sambutan Administrator -->
<div class="bg-gradient-to-r from-[#1E6BFF] via-[#3B82F6] to-[#4F46E5] rounded-3xl p-6 lg:p-8 text-white relative overflow-hidden shadow-[0_10px_30px_rgba(30,107,255,0.25)]">
    <!-- Dekorasi Background Bulatan Halus -->
    <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
    <div class="absolute right-32 -top-12 w-56 h-56 rounded-full bg-indigo-300/20 blur-xl pointer-events-none"></div>

    <div class="relative z-10 max-w-2xl">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-white text-xs font-medium mb-3 border border-white/20">
            🛡️ Administrator Control Center
        </span>
        <h2 class="text-2xl lg:text-3xl font-bold mb-2 tracking-tight">
            Selamat Datang, {{ auth()->user()->name ?? 'Administrator' }}!
        </h2>
        <p class="text-white/85 text-xs lg:text-sm leading-relaxed mb-6">
            Sebagai administrator utama sekolah, Anda memiliki hak penuh untuk menambahkan akun guru mapel bahasa Inggris baru, memperbarui profil & NIP guru, serta mereset kata sandi pengajar bila diperlukan.
        </p>
        <div class="flex flex-wrap gap-3">
            <button type="button" 
                    onclick="openTambahGuruModal()" 
                    class="px-5 py-2.5 rounded-xl bg-white text-[#1E6BFF] font-semibold text-xs hover:bg-blue-50 transition-all shadow-md inline-flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Akun Guru Baru
            </button>
            <a href="{{ route('guru.siswa.index') }}" 
               class="px-5 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-semibold text-xs border border-white/20 transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Lihat Data Siswa
            </a>
        </div>
    </div>
</div>
