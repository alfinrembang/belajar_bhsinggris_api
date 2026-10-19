<!-- Siswa Paling Unggul (Top 5 Leaderboard) -->
<div class="bg-white rounded-3xl p-6 lg:p-7 border border-slate-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.02)] space-y-5">
    
    <!-- Header Kartu -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="7"></circle>
                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base">Siswa Paling Unggul</h3>
                <p class="text-xs text-slate-400">Peringkat 5 siswa dengan capaian XP tertinggi</p>
            </div>
        </div>

        <a href="{{ route('guru.laporan.index') }}" 
           class="text-xs font-bold text-[#1E6BFF] hover:text-[#155BE5] transition-colors">
            Leger Nilai
        </a>
    </div>

    <!-- Daftar Top 5 Siswa -->
    @if(isset($topSiswa) && $topSiswa->isNotEmpty())
        <div class="space-y-3">
            @foreach($topSiswa as $index => $item)
                @php
                    $rank = $index + 1;
                    $rankColor = match($rank) {
                        1 => 'bg-amber-100 text-amber-800 border-amber-200',
                        2 => 'bg-slate-100 text-slate-700 border-slate-200',
                        3 => 'bg-orange-100 text-orange-800 border-orange-200',
                        default => 'bg-slate-50 text-slate-500 border-slate-100',
                    };
                @endphp
                <a href="{{ route('guru.siswa.show', $item['id']) }}" 
                   class="p-3 rounded-2xl bg-slate-50/70 hover:bg-blue-50/60 border border-slate-100 hover:border-blue-200/80 transition-all flex items-center justify-between gap-3 group">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Rank Badge -->
                        <span class="w-6 h-6 rounded-lg {{ $rankColor }} border text-[11px] font-extrabold flex items-center justify-center shrink-0">
                            {{ $rank }}
                        </span>

                        <!-- Avatar Inisial -->
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#1E6BFF] to-[#0A56E2] text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-xs">
                            {{ $item['avatar'] }}
                        </div>

                        <!-- Data Siswa -->
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 group-hover:text-[#1E6BFF] truncate transition-colors">
                                {{ $item['nama_lengkap'] }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-medium truncate">
                                {{ $item['kelas_lengkap'] }}
                            </p>
                        </div>
                    </div>

                    <!-- XP & Nilai -->
                    <div class="text-right shrink-0">
                        <span class="px-2 py-0.5 rounded-md bg-amber-50 border border-amber-200/70 text-amber-800 font-bold text-xs inline-flex items-center gap-1">
                            <svg class="w-3 h-3 text-amber-500" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            {{ number_format($item['total_xp']) }} XP
                        </span>
                        <p class="text-[10px] text-slate-400 mt-0.5 font-medium">
                            Nilai: <strong class="text-slate-700 font-bold">{{ $item['nilai_akhir'] }}</strong>
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="py-8 text-center text-slate-400">
            <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="8" r="7"></circle>
                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
            </svg>
            <p class="text-xs font-medium">Belum ada progres siswa yang tercatat</p>
            <a href="{{ route('guru.siswa.index') }}" class="text-xs font-bold text-[#1E6BFF] hover:underline mt-1 inline-block">Lihat Data Siswa</a>
        </div>
    @endif

</div>
