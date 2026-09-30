<x-layout 
    title="Manajemen Materi - Study English Prima" 
    pageTitle="Manajemen Materi" 
    pageSubtitle="Kelola modul pembelajaran, materi bacaan, audio pengucapan, dan latihan soal siswa." 
    active="materi"
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

    <!-- Bar Aksi Atas: Pencarian, Filter Kategori & Kelas, serta Tombol Tambah -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs">
        <!-- Form Pencarian & Filter -->
        <form method="GET" action="{{ route('guru.materi.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Input Cari Judul -->
            <div class="relative w-full sm:w-64">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari materi pembelajaran..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
            </div>

            <!-- Filter Kelas -->
            <select name="kelas" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="">Semua Tingkat Kelas</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>

            <!-- Filter Kategori -->
            <select name="kategori" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="">Semua Kategori</option>
                @if(isset($kategoriList) && $kategoriList->isNotEmpty())
                    @foreach($kategoriList as $kat)
                        @php $kNama = is_object($kat) ? $kat->nama : $kat; @endphp
                        <option value="{{ $kNama }}" {{ request('kategori') == $kNama ? 'selected' : '' }}>{{ $kNama }}</option>
                    @endforeach
                @else
                    <option value="Grammar" {{ request('kategori') == 'Grammar' ? 'selected' : '' }}>Grammar</option>
                    <option value="Reading" {{ request('kategori') == 'Reading' ? 'selected' : '' }}>Reading</option>
                    <option value="Conversation" {{ request('kategori') == 'Conversation' ? 'selected' : '' }}>Conversation</option>
                    <option value="Vocabulary" {{ request('kategori') == 'Vocabulary' ? 'selected' : '' }}>Vocabulary</option>
                @endif
            </select>

            @if(request()->hasAny(['q', 'kelas', 'kategori']))
                <a href="{{ route('guru.materi.index') }}" class="text-xs text-rose-600 hover:underline font-semibold py-2">Reset</a>
            @endif
        </form>

        <!-- Tombol Tambah Materi -->
        <a href="{{ route('guru.materi.create') }}" 
           class="px-5 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-semibold text-xs shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Materi Baru
        </a>
    </div>

    <!-- Ringkasan Statistik Cepat Materi -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-lg shrink-0">
                📚
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Modul Materi</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ $totalMateri }} Modul</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                ✅
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Materi Aktif Tayang</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ $totalAktif }} Modul</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg shrink-0">
                📱
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Sinkronisasi Flutter</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">Real-time Live</h4>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Modul Materi -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Daftar Modul Materi Pembelajaran</h3>
                <p class="text-xs text-slate-400 mt-0.5">Materi terintegrasi dengan audio listening & latihan soal siswa</p>
            </div>
            <span class="text-xs text-slate-400">Total: {{ $materis->total() }} Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Modul & Judul Materi</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6">Tingkat Kelas</th>
                        <th class="py-3.5 px-6">Konten & Fitur</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($materis as $index => $materi)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ str_pad($materis->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <p class="font-bold text-slate-800 text-sm truncate">{{ $materi->judul }}</p>
                                        <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $materi->deskripsi_singkat ?: 'Tidak ada ringkasan' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $badgeColor = match($materi->kategori) {
                                        'Reading' => 'bg-blue-50 text-[#1E6BFF]',
                                        'Grammar' => 'bg-emerald-50 text-emerald-600',
                                        'Conversation' => 'bg-purple-50 text-purple-600',
                                        'Vocabulary' => 'bg-amber-50 text-amber-600',
                                        default => 'bg-slate-50 text-slate-600',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full {{ $badgeColor }} text-[11px] font-semibold">
                                    {{ $materi->kategori }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-600 font-semibold">
                                {{ $materi->tingkat_kelas === 'Semua Kelas' ? 'Semua Kelas' : 'Kelas ' . $materi->tingkat_kelas }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-1 text-[11px]">
                                    <span class="inline-flex items-center gap-1 font-semibold text-slate-700">
                                        🎯 {{ $materi->soals_count }} Soal Latihan
                                    </span>
                                    @if($materi->audio_materi)
                                        <span class="inline-flex items-center gap-1 text-emerald-600 font-semibold">
                                            🎧 Ada Audio MP3
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($materi->status === 'aktif')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[11px] font-semibold">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Preview Siswa -->
                                    <a href="{{ route('guru.materi.show', $materi->id) }}" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" 
                                       title="Lihat Tampilan Siswa">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a href="{{ route('guru.materi.edit', $materi->id) }}" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-[#1E6BFF] hover:bg-blue-50 transition-colors" 
                                       title="Edit Materi">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('guru.materi.destroy', $materi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus modul materi ini beserta seluruh soal latihannya?');" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" 
                                                title="Hapus Materi">
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
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl">
                                        📖
                                    </div>
                                    <p class="font-semibold text-slate-600">Belum ada modul materi pembelajaran.</p>
                                    <p class="text-xs text-slate-400">Klik tombol <strong>+ Tambah Materi Baru</strong> untuk membuat modul pembelajaran pertama.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($materis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $materis->links() }}
            </div>
        @endif
    </div>
</x-layout>
