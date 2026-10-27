<!-- Ringkasan Ketuntasan Belajar & Performa Siswa (Model Donut Chart) -->
<div class="bg-white rounded-3xl p-6 lg:p-7 border border-slate-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.02)] space-y-6">
    
    <!-- Header Kartu -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#EAF5EF] text-[#2E7352] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="font-bold text-slate-900 text-base">Ketuntasan & Rerata Nilai Siswa</h3>
                    @if ($isFilterKelas)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#EAF5EF] text-[#2E7352] border border-[#D7EDE1]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#4EA87D] animate-pulse"></span>
                            Kelas Diampu: {{ $labelKelasDiampu }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            Seluruh Kelas
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 mt-0.5">Ringkasan capaian belajar siswa pada seluruh modul pembelajaran aktif</p>
            </div>
        </div>

        <a href="{{ route('guru.laporan.index') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-bold text-[#4EA87D] hover:text-[#3E946B] self-start sm:self-auto transition-colors group">
            <span>Buka Laporan Lengkap</span>
            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </a>
    </div>

    <!-- Tampilan Utama: Donut Chart Melingkar (Kiri) & Rincian Metrik Vertikal (Kanan) sesuai Gambar 1 -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 lg:gap-8 items-center pt-1">
        
        <!-- Bagian Donut Chart Lingkaran (5 Kolom di Desktop) -->
        <div class="md:col-span-5 flex flex-col items-center justify-center p-3 sm:p-4 bg-slate-50/60 rounded-3xl border border-slate-100/80">
            <x-donut-chart 
                :center-value="$rataKeseluruhan" 
                center-label="Rerata" 
                center-subtext="skala 100" 
                :deg-materi="$degMateri" 
                :deg-listening="$degListening" 
                size="w-44 h-44 sm:w-48 sm:h-48"
            />

            <!-- Keterangan Status KKM -->
            <div class="mt-4 text-center space-y-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $rataKeseluruhan >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/80' : 'bg-amber-50 text-amber-700 border border-amber-200/80' }}">
                    <span class="w-2 h-2 rounded-full {{ $rataKeseluruhan >= 75 ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    {{ $rataKeseluruhan >= 75 ? 'Rata-rata di Atas KKM (75)' : 'Perlu Pendampingan Tambahan' }}
                </span>
                <p class="text-[11px] text-slate-400 font-medium">
                    Bobot: 30% Teori • 30% Audio • 40% Kuis
                </p>
            </div>
        </div>

        <!-- Bagian Rincian Metrik Vertikal (7 Kolom di Desktop) -->
        <div class="md:col-span-7 flex flex-col justify-center space-y-3">
            
            <!-- 1. Latihan Teori Materi (Hijau Susu) -->
            <x-donut-metric-row 
                label="Latihan Teori Materi"
                :value="$rataMateri"
                color="green"
                subtext="{{ $totalMateriSelesai }} Selesai"
                :max="100"
            />

            <!-- 2. Listening Audio (Aksen Pink Lembut) -->
            <x-donut-metric-row 
                label="Listening Audio"
                :value="$rataListening"
                color="pink"
                subtext="{{ $totalListeningSelesai }} Selesai"
                :max="100"
            />

            <!-- 3. Paket Kuis & Ujian (Warm Amber) -->
            <x-donut-metric-row 
                label="Paket Kuis & Ujian"
                :value="$rataQuiz"
                color="amber"
                subtext="{{ $totalQuizSelesai }} Selesai"
                :max="100"
            />

        </div>

    </div>

    <!-- Footer Status & Total Sesi Tuntas -->
    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between text-xs text-slate-500 gap-2">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Total <strong class="text-slate-800 font-bold">{{ $totalAktivitasSelesai }}</strong> sesi belajar telah dituntaskan oleh siswa</span>
        </div>
        <span class="text-[11px] text-slate-400 font-medium">
            @if ($isFilterKelas)
                Memantau {{ $totalSiswaDiampu }} siswa dari kelas yang diampu
            @else
                Memantau seluruh {{ $totalSiswa }} siswa terdaftar
            @endif
        </span>
    </div>

</div>
