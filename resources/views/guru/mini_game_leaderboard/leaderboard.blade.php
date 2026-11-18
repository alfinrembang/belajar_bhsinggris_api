<div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.02)] space-y-5">
    
    <!-- Header Papan Peringkat -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-200/70">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="7"></circle>
                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                </svg>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Papan Peringkat Game</h3>
                <p class="text-xs text-slate-400">Akumulasi perolehan skor aktual siswa.</p>
            </div>
        </div>

        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] text-[11px] font-bold">
            <span class="w-1.5 h-1.5 rounded-full bg-[#4EA87D]"></span>
            Skor Nyata
        </span>
    </div>

    <!-- Daftar Siswa Ranked Leaderboard -->
    <div class="space-y-3">
        @forelse($rankedLeaderboard as $item)
            @php
                $rank = $item['rank'];
                
                // Konfigurasi visual podium berkelas (tanpa emoji generik)
                $rankBadgeClass = match($rank) {
                    1 => 'bg-gradient-to-br from-amber-400 to-amber-600 text-white shadow-[0_2px_8px_rgba(217,119,6,0.3)]',
                    2 => 'bg-gradient-to-br from-slate-400 to-slate-600 text-white shadow-[0_2px_8px_rgba(71,85,105,0.25)]',
                    3 => 'bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-[0_2px_8px_rgba(194,65,12,0.25)]',
                    default => 'bg-slate-100 text-slate-600 border border-slate-200/80 font-bold',
                };

                $cardBorderClass = match($rank) {
                    1 => 'border-amber-200/90 bg-gradient-to-r from-amber-50/50 via-white to-white',
                    2 => 'border-slate-200/90 bg-gradient-to-r from-slate-100/40 via-white to-white',
                    3 => 'border-orange-200/80 bg-gradient-to-r from-orange-50/40 via-white to-white',
                    default => 'border-slate-100 bg-slate-50/50 hover:bg-white',
                };

                $avatarGradient = match($rank) {
                    1 => '!from-amber-500 !to-amber-600',
                    2 => '!from-slate-500 !to-slate-600',
                    3 => '!from-orange-500 !to-orange-600',
                    default => '!from-[#4EA87D] !to-[#3E946B]',
                };
            @endphp
            
            <div class="p-3.5 rounded-2xl border {{ $cardBorderClass }} transition-all flex items-center justify-between gap-3 shadow-2xs">
                <div class="flex items-center gap-3 min-w-0">
                    
                    <!-- Lencana Nomor Peringkat -->
                    <span class="w-7 h-7 rounded-xl {{ $rankBadgeClass }} text-xs font-black flex items-center justify-center shrink-0">
                        {{ $rank }}
                    </span>

                    <!-- Avatar Inisial Siswa -->
                    <x-avatar-initial :name="$item['nama']" size="sm" class="{{ $avatarGradient }}" />

                    <!-- Rincian Identitas Siswa -->
                    <div class="min-w-0">
                        <p class="font-extrabold text-xs text-slate-900 truncate">
                            {{ $item['nama'] }}
                        </p>
                        <p class="text-[11px] text-slate-400 font-medium truncate mt-0.5">
                            {{ $item['kelas'] }} • No. {{ $item['no_absen'] }}
                        </p>
                        <div class="flex items-center gap-2 mt-1 text-[10px] text-slate-500">
                            <span class="inline-flex items-center gap-1 font-semibold text-sky-700 bg-sky-50 px-1.5 py-0.5 rounded border border-sky-100">
                                <span>Memancing:</span>
                                <span>{{ number_format($item['skor_memancing']) }} pts</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Perolehan Total Poin -->
                <div class="text-right shrink-0">
                    <span class="font-mono font-black text-sm text-[#2E7352]">
                        {{ number_format($item['total_skor']) }}
                    </span>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                        POIN
                    </p>
                </div>
            </div>

        @empty
            <div class="py-10 text-center space-y-2">
                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                </div>
                <p class="text-xs font-semibold text-slate-700">Belum Ada Riwayat Permainan</p>
                <p class="text-[11px] text-slate-400">Papan peringkat akan otomatis terisi saat siswa mulai memainkan game di mobile.</p>
            </div>
        @endforelse
    </div>

</div>
