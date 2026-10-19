<!-- Daftar Modul & Bahan Ajar Materi Terbaru -->
<div id="modul-terbaru" class="bg-white rounded-3xl p-6 lg:p-7 border border-slate-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.02)] space-y-5">
    
    <!-- Header Kartu -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 text-base">Modul Pembelajaran Terkini</h3>
                <p class="text-xs text-slate-400">Bahan ajar materi pembelajaran yang diterbitkan guru</p>
            </div>
        </div>

        <a href="{{ route('guru.materi.index') }}" 
           class="text-xs font-bold text-[#1E6BFF] hover:text-[#155BE5] transition-colors">
            Lihat Semua
        </a>
    </div>

    <!-- List 5 Materi Paginated -->
    @if($materiTerbaru->isNotEmpty())
        <div class="divide-y divide-slate-100">
            @foreach($materiTerbaru as $materi)
                <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-3 group">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1E6BFF] border border-blue-100/80 flex items-center justify-center shrink-0 group-hover:bg-[#1E6BFF] group-hover:text-white transition-all shadow-xs">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('guru.materi.show', $materi) }}" class="text-xs font-bold text-slate-800 group-hover:text-[#1E6BFF] truncate block transition-colors">
                                {{ $materi->judul }}
                            </a>
                            <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-400">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-semibold text-[10px]">
                                    {{ $materi->kategori ?: 'General' }}
                                </span>
                                <span>Kelas {{ $materi->tingkat_kelas ?: 'Semua' }}</span>
                                <span>·</span>
                                <span class="text-amber-600 font-medium">+{{ $materi->xp_reward ?? 50 }} XP</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('guru.materi.show', $materi) }}" 
                       class="p-2 rounded-xl text-slate-400 group-hover:text-[#1E6BFF] group-hover:bg-blue-50 transition-colors shrink-0"
                       title="Buka Detail">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Custom Pagination Links Sesuai Mockup -->
        {{ $materiTerbaru->links('components.pagination') }}
    @else
        <div class="py-8 text-center text-slate-400">
            <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
            <p class="text-xs font-medium">Belum ada modul yang ditambahkan</p>
            <a href="{{ route('guru.materi.create') }}" class="text-xs font-bold text-[#1E6BFF] hover:underline mt-1 inline-block">Tambah Modul Pertama</a>
        </div>
    @endif

</div>
