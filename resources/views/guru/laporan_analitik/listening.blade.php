<x-layout 
    title="Rekap Nilai Listening - Study English Prima" 
    pageTitle="Rekap Nilai Modul Listening" 
    pageSubtitle="Rekapitulasi lengkap riwayat pengerjaan audio listening pemahaman siswa, perolehan skor, dan status ketuntasan KKM." 
    active="listening"
>
    <!-- Tab Navigasi Sub-Modul Modul Listening -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 bg-white p-3 rounded-2xl border border-slate-100 shadow-xs">
        <div class="flex items-center gap-1.5 flex-wrap">
            <a href="{{ route('guru.listening.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                </svg>
                <span>Daftar Modul Rekaman</span>
            </a>
            <a href="{{ route('guru.laporan.listening') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-[#4EA87D] text-white shadow-xs">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span>Rekap Nilai Listening</span>
            </a>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('guru.laporan.listening.export_excel', request()->query()) }}" 
               class="px-4 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/90 text-emerald-800 text-xs font-bold transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="8" y1="13" x2="16" y2="13"></line>
                    <line x1="8" y1="17" x2="16" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span>Export Excel (.xlsx)</span>
            </a>

            <a href="{{ route('guru.laporan.listening.cetak', request()->query()) }}" 
               target="_blank"
               class="px-4 py-2 rounded-xl bg-[#4EA87D] hover:bg-[#43966E] text-white text-xs font-bold shadow-[0_4px_14px_rgba(78,168,125,0.25)] transition-all flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Rekap Listening</span>
            </a>
        </div>
    </div>

    <!-- Filter & Pencarian Rekap Listening -->
    <div class="bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs mb-6">
        <form method="GET" action="{{ route('guru.laporan.listening') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Pencarian Siswa -->
            <div class="relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Cari nama / NIS..." 
                       class="w-full pl-9 pr-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
            </div>

            <!-- Filter Modul Listening -->
            <select name="modul_listening_id" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Modul Listening</option>
                @foreach($daftarModulListening as $modul)
                    <option value="{{ $modul->id }}" {{ request('modul_listening_id') == $modul->id ? 'selected' : '' }}>
                        {{ Str::limit($modul->judul, 25) }}
                    </option>
                @endforeach
            </select>

            <!-- Filter Kelas & Rombel (Dinamis dari Data Siswa) -->
            <select name="rombel" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Kelas & Rombel</option>
                @foreach($daftarRombelGrouped as $tingkat => $rombels)
                    <optgroup label="Tingkat Kelas {{ $tingkat }}">
                        <option value="tingkat:{{ $tingkat }}" {{ request('rombel') === 'tingkat:'.$tingkat || (request('kelas') == $tingkat && !request('rombel') && !request('jurusan')) ? 'selected' : '' }}>
                            📁 Semua Kelas {{ $tingkat }}
                        </option>
                        @foreach($rombels as $itemRombel)
                            <option value="{{ $itemRombel }}" {{ request('rombel') === $itemRombel ? 'selected' : '' }}>
                                {{ $itemRombel }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>

            <!-- Filter Status Kelulusan KKM -->
            <div class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="flex-1 px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                    <option value="">Semua Status</option>
                    <option value="tuntas" {{ request('status') == 'tuntas' ? 'selected' : '' }}>Tuntas (≥{{ $kkm }})</option>
                    <option value="remedial" {{ request('status') == 'remedial' ? 'selected' : '' }}>Remedial (<{{ $kkm }})</option>
                </select>

                @if(request('q') || request('modul_listening_id') || request('kelas') || request('jurusan') || request('rombel') || request('status'))
                    <a href="{{ route('guru.laporan.listening') }}" 
                       class="p-2.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition-colors"
                       title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Ringkasan Statistik Evaluasi Listening -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Peserta Listening</p>
                <h3 class="text-xl font-black text-slate-800">{{ $totalRiwayat }} Pengerjaan</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rerata Skor Listening</p>
                <h3 class="text-xl font-black text-slate-800">{{ $rataRataSkor }} <span class="text-xs text-slate-400 font-medium">/ 100</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tuntas KKM (≥{{ $kkm }})</p>
                <h3 class="text-xl font-black text-[#2E7352]">{{ $totalTuntas }} Siswa</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Perlu Remedial</p>
                <h3 class="text-xl font-black text-rose-700">{{ $totalRemedial }} Siswa</h3>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi Nilai Listening -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-slate-800 text-sm">Daftar Hasil Nilai Listening Siswa</h3>
                <p class="text-xs text-slate-400 mt-0.5">Rincian skor audio listening pemahaman, ketuntasan KKM, dan tanggal pengerjaan</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-50 text-slate-600 border border-slate-100">
                Standar KKM: <strong>{{ $kkm }}</strong>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Siswa</th>
                        <th class="py-3.5 px-4">Rombel Kelas</th>
                        <th class="py-3.5 px-4">Modul Listening</th>
                        <th class="py-3.5 px-4 text-center">Tingkat</th>
                        <th class="py-3.5 px-4 text-center">Skor Nilai</th>
                        <th class="py-3.5 px-4 text-center">Status KKM</th>
                        <th class="py-3.5 px-4 text-center">Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($riwayatListening as $index => $item)
                        @php
                            $isTuntas = ($item->skor ?? 0) >= $kkm;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                {{ $riwayatListening->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800">{{ $item->siswa?->nama_lengkap ?? 'Siswa Dihapus' }}</div>
                                <div class="text-[11px] text-slate-400">NISN: {{ $item->siswa?->nisn ?: '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-semibold text-slate-700 text-[11px]">
                                    {{ $item->siswa?->kelas_lengkap ?: '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800">{{ $item->modulListening?->judul ?? 'Modul Dihapus' }}</div>
                                <div class="text-[11px] text-slate-400">Durasi: {{ $item->modulListening?->durasi ?: '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-sky-50 text-sky-700 border border-sky-100">
                                    {{ $item->modulListening?->tingkat_kesulitan ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-black text-sm {{ $isTuntas ? 'text-[#2E7352]' : 'text-rose-600' }}">
                                    {{ $item->skor ?? 0 }}
                                </span>
                                <span class="text-[10px] text-slate-400 block">/ 100</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($isTuntas)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-[#EAF5EF] text-[#2E7352] border border-[#D7EDE1]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#4EA87D]"></span>
                                        Tuntas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Remedial
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center text-slate-500 text-[11px]">
                                {{ $item->completed_at ? \Carbon\Carbon::parse($item->completed_at)->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                                        <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-slate-700 text-sm">Belum Ada Riwayat Pengerjaan Listening</h4>
                                <p class="text-xs text-slate-400 mt-1">Data pengerjaan audio percakapan siswa akan tampil di sini secara otomatis.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayatListening->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                {{ $riwayatListening->links() }}
            </div>
        @endif
    </div>
</x-layout>
