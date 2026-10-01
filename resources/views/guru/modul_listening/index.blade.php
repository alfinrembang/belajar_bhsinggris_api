<x-layout 
    title="Modul Listening - Study English Prima" 
    pageTitle="Modul Listening" 
    pageSubtitle="Kelola rekaman audio percakapan, transkrip dialog, dan latihan listening pemahaman siswa." 
    active="listening"
>
    <!-- Notifikasi Flash Message Sukses -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm shrink-0">
                    ✓
                </div>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-base cursor-pointer">×</button>
        </div>
    @endif

    <!-- Notifikasi Flash Message Error -->
    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-sm shrink-0">
                    !
                </div>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold text-base cursor-pointer">×</button>
        </div>
    @endif

    <!-- Bar Aksi Atas: Pencarian, Filter & Tombol Tambah Audio -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs mb-6">
        <!-- Form Filter & Pencarian -->
        <form method="GET" action="{{ route('guru.listening.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Input Search -->
            <div class="relative w-full sm:w-72">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari topik atau audio percakapan..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
            </div>

            <!-- Filter Tingkat Kesulitan -->
            <select name="tingkat" onchange="this.form.submit()" class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="">Semua Tingkat</option>
                <option value="Beginner" {{ request('tingkat') == 'Beginner' ? 'selected' : '' }}>Beginner (Pemula)</option>
                <option value="Intermediate" {{ request('tingkat') == 'Intermediate' ? 'selected' : '' }}>Intermediate (Menengah)</option>
                <option value="Advanced" {{ request('tingkat') == 'Advanced' ? 'selected' : '' }}>Advanced (Lanjutan)</option>
            </select>

            <!-- Filter Kelas -->
            <select name="kelas" onchange="this.form.submit()" class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="">Semua Kelas</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
                <option value="Semua Kelas" {{ request('kelas') == 'Semua Kelas' ? 'selected' : '' }}>Semua Kelas (Umum)</option>
            </select>

            @if(request()->hasAny(['q', 'tingkat', 'kelas']))
                <a href="{{ route('guru.listening.index') }}" class="text-xs text-rose-600 hover:underline font-semibold py-2">Reset</a>
            @endif
        </form>

        <!-- Tombol Tambah Audio Listening Baru -->
        <a href="{{ route('guru.listening.create') }}" 
           class="px-5 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-semibold text-xs shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all flex items-center justify-center gap-2 shrink-0">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Unggah Audio Baru
        </a>
    </div>

    <!-- Ringkasan Statistik Listening -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5 mb-6">
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-lg shrink-0">
                🎧
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Track Audio</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ $totalAudio }} Paket</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                ✅
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Track Aktif Tayang</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ $totalAktif }} Track</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg shrink-0">
                📝
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Latihan Soal</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ $totalSoal }} Pertanyaan</h4>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Track Listening -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Daftar Modul Rekaman Audio</h3>
            <span class="text-xs text-slate-400">Sinkron otomatis dengan menu listening aplikasi siswa</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Judul Percakapan & Audio</th>
                        <th class="py-3.5 px-6">Tingkat Kesulitan</th>
                        <th class="py-3.5 px-6">Durasi</th>
                        <th class="py-3.5 px-6">Latihan Soal</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($listenings as $item)
                        @php
                            $levelColor = match($item->tingkat_kesulitan) {
                                'Intermediate' => 'bg-purple-50 text-purple-600 border border-purple-200/80',
                                'Advanced' => 'bg-rose-50 text-rose-600 border border-rose-200/80',
                                default => 'bg-blue-50 text-[#1E6BFF] border border-blue-200/80',
                            };
                            $iconBg = match($item->tingkat_kesulitan) {
                                'Intermediate' => 'bg-purple-50 text-purple-600',
                                'Advanced' => 'bg-rose-50 text-rose-600',
                                default => 'bg-blue-50 text-[#1E6BFF]',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl {{ $iconBg }} flex items-center justify-center font-bold text-sm shrink-0">
                                        🎧
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('guru.listening.show', $item->id) }}" class="font-bold text-slate-800 hover:text-[#1E6BFF] transition-colors line-clamp-1">
                                            {{ $item->judul }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 truncate max-w-sm">
                                            {{ $item->deskripsi ?? 'Tidak ada ringkasan deskripsi' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-[11px] font-semibold {{ $levelColor }}">
                                    {{ $item->tingkat_kesulitan }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-600 font-mono">
                                {{ $item->durasi ?? '00:00' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 text-slate-700 font-semibold">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    {{ $item->soals_count }} Soal
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($item->status === 'aktif')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[11px] font-semibold">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('guru.listening.show', $item->id) }}" class="p-2 rounded-xl text-slate-400 hover:text-[#1E6BFF] hover:bg-blue-50 transition-colors" title="Lihat Detail Audio & Soal">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <a href="{{ route('guru.listening.edit', $item->id) }}" class="p-2 rounded-xl text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit Modul">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <!-- Form Hapus Eksplisit POST -->
                                    <form action="{{ route('guru.listening.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket modul listening ini?');" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Modul">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3 text-xl">
                                    🎧
                                </div>
                                <p class="text-sm font-semibold text-slate-700">Belum Ada Modul Listening</p>
                                <p class="text-xs text-slate-400 mt-1">Unggah audio rekaman percakapan pertama untuk pembelajaran listening siswa.</p>
                                <a href="{{ route('guru.listening.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-2xl bg-[#1E6BFF] text-white text-xs font-bold hover:bg-[#155BE5] transition-all shadow-xs">
                                    + Unggah Audio Baru
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($listenings->hasPages())
            <div class="p-5 border-t border-slate-100">
                {{ $listenings->links() }}
            </div>
        @endif
    </div>
</x-layout>
