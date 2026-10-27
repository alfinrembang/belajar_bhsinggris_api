<!-- Kartu Statistik Ringkasan Eksekutif (4 Pilar Utama: Hijau Susu & Aksen Pink) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
    
    <!-- Kartu 1: Total Siswa Terdaftar (Hijau Susu) -->
    <a href="{{ route('guru.siswa.index') }}" 
       class="group bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:shadow-md hover:border-[#D7EDE1] transition-all flex items-start justify-between cursor-pointer">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Siswa Terdaftar</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-1 tracking-tight group-hover:text-[#4EA87D] transition-colors">
                {{ $totalSiswa ?? 0 }}
            </h3>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] text-slate-500 font-medium">
                <span class="px-1.5 py-0.5 rounded bg-[#EAF5EF] text-[#2E7352] font-semibold">X: {{ $siswaKelas10 ?? 0 }}</span>
                <span class="px-1.5 py-0.5 rounded bg-[#EAF5EF] text-[#2E7352] font-semibold">XI: {{ $siswaKelas11 ?? 0 }}</span>
                <span class="px-1.5 py-0.5 rounded bg-[#EAF5EF] text-[#2E7352] font-semibold">XII: {{ $siswaKelas12 ?? 0 }}</span>
            </div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0 group-hover:bg-[#4EA87D] group-hover:text-white transition-all shadow-xs">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
    </a>

    <!-- Kartu 2: Modul Bahan Ajar Teori (Hijau Susu) -->
    <a href="{{ route('guru.materi.index') }}" 
       class="group bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:shadow-md hover:border-[#D7EDE1] transition-all flex items-start justify-between cursor-pointer">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Modul Bahan Ajar</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-1 tracking-tight group-hover:text-[#4EA87D] transition-colors">
                {{ $totalMateri ?? 0 }}
            </h3>
            <p class="text-[11px] text-slate-500 font-medium mt-2 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#4EA87D]"></span>
                <span>{{ $totalKategori ?? 0 }} Kategori Topik Aktif</span>
            </p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0 group-hover:bg-[#4EA87D] group-hover:text-white transition-all shadow-xs">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
        </div>
    </a>

    <!-- Kartu 3: Modul Listening Audio (Aksen Pink Lembut) -->
    <a href="{{ route('guru.listening.index') }}" 
       class="group bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:shadow-md hover:border-[#F9C5D1] transition-all flex items-start justify-between cursor-pointer">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Modul Audio Listening</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-1 tracking-tight group-hover:text-[#E11D48] transition-colors">
                {{ $totalListening ?? 0 }}
            </h3>
            <p class="text-[11px] text-slate-500 font-medium mt-2 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#FB7185]"></span>
                <span>Audio Percakapan Siswa</span>
            </p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-[#FDF2F4] border border-[#F9C5D1] text-[#E11D48] flex items-center justify-center shrink-0 group-hover:bg-[#FB7185] group-hover:text-white transition-all shadow-xs">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
            </svg>
        </div>
    </a>

    <!-- Kartu 4: Paket Kuis & Evaluasi (Hijau Susu) -->
    <a href="{{ route('guru.quiz.index') }}" 
       class="group bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:shadow-md hover:border-[#D7EDE1] transition-all flex items-start justify-between cursor-pointer">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Paket Kuis & Soal</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-1 tracking-tight group-hover:text-[#4EA87D] transition-colors">
                {{ $totalQuiz ?? 0 }}
            </h3>
            <p class="text-[11px] text-[#2E7352] font-medium mt-2 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#4EA87D]"></span>
                <span>Siap Dikerjakan Siswa</span>
            </p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0 group-hover:bg-[#4EA87D] group-hover:text-white transition-all shadow-xs">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                <path d="M9 9h6"></path>
                <path d="M9 13h6"></path>
                <path d="M9 17h4"></path>
            </svg>
        </div>
    </a>

</div>
