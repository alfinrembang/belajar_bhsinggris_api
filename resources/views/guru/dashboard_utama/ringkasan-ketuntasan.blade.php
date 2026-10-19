<!-- Ringkasan Ketuntasan Belajar & Performa Siswa -->
<div class="bg-white rounded-3xl p-6 lg:p-7 border border-slate-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.02)] space-y-6">
    
    <!-- Header Kartu -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base">Ketuntasan & Rerata Nilai Siswa</h3>
                <p class="text-xs text-slate-400">Ringkasan capaian belajar siswa pada seluruh modul pembelajaran aktif</p>
            </div>
        </div>

        <a href="{{ route('guru.laporan.index') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-bold text-[#1E6BFF] hover:text-[#155BE5] self-start sm:self-auto transition-colors group">
            <span>Buka Laporan Lengkap</span>
            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </a>
    </div>

    <!-- Metrik Nilai Rerata Keseluruhan -->
    <div class="bg-slate-50/80 rounded-2xl p-5 border border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Rata-rata Skor Keseluruhan</p>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $rataKeseluruhan }}</span>
                <span class="text-sm font-bold text-slate-400">/ 100</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-1">
                Akumulasi bobot: 30% Teori Materi + 30% Listening + 40% Kuis
            </p>
        </div>

        <div class="sm:text-right">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $rataKeseluruhan >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/80' : 'bg-amber-50 text-amber-700 border border-amber-200/80' }}">
                <span class="w-2 h-2 rounded-full {{ $rataKeseluruhan >= 75 ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                {{ $rataKeseluruhan >= 75 ? 'Rata-rata di Atas KKM (75)' : 'Perlu Pendampingan Tambahan' }}
            </span>
            <p class="text-[11px] text-slate-400 mt-1.5 font-medium">
                Total <strong class="text-slate-700 font-bold">{{ $totalAktivitasSelesai }}</strong> sesi belajar telah dituntaskan siswa
            </p>
        </div>
    </div>

    <!-- Rincian 3 Modul Pembelajaran Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <!-- 1. Materi Modul -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700">Latihan Teori Materi</span>
                <span class="text-xs font-extrabold text-blue-600">{{ $rataMateri }}</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-[#1E6BFF] h-2 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $rataMateri)) }}%"></div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-400">
                <span>{{ $totalMateriSelesai }} Selesai</span>
                <span>Skala 100</span>
            </div>
        </div>

        <!-- 2. Listening Audio -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700">Listening Audio</span>
                <span class="text-xs font-extrabold text-purple-600">{{ $rataListening }}</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-purple-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $rataListening)) }}%"></div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-400">
                <span>{{ $totalListeningSelesai }} Selesai</span>
                <span>Skala 100</span>
            </div>
        </div>

        <!-- 3. Paket Kuis & Ujian -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700">Paket Kuis & Ujian</span>
                <span class="text-xs font-extrabold text-emerald-600">{{ $rataQuiz }}</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $rataQuiz)) }}%"></div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-400">
                <span>{{ $totalQuizSelesai }} Selesai</span>
                <span>Skala 100</span>
            </div>
        </div>

    </div>

</div>
