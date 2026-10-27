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
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
            </div>

            <!-- Filter Kelas -->
            <select name="kelas" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Tingkat Kelas</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>

            <!-- Filter Kategori -->
            <select name="kategori" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
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
        <x-button variant="create" href="{{ route('guru.materi.create') }}">
            Tambah Materi Baru
        </x-button>
    </div>

    <!-- Ringkasan Statistik Cepat Materi -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <x-stat-card title="Total Modul Materi" :value="$totalMateri . ' Modul'" icon="book" color="blue" />
        <x-stat-card title="Materi Aktif Tayang" :value="$totalAktif . ' Modul'" icon="check" color="emerald" />
        <x-stat-card title="Sinkronisasi Flutter" value="Real-time Live" icon="mobile" color="purple" />
    </div>

    <!-- Tabel Daftar Modul Materi -->
    <!-- Tabel Daftar Modul Materi Pembelajaran -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Daftar Modul Materi Pembelajaran</h3>
                <p class="text-xs text-slate-400 mt-0.5">Materi terintegrasi dengan audio listening & latihan soal siswa</p>
            </div>
            <span class="text-xs text-slate-500 font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200/60">Total: {{ $materis->total() }} Data</span>
        </div>

        <div class="w-full overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/70 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4 w-[34%]">Modul & Judul Materi</th>
                        <th class="py-3 px-2.5 w-[13%]">Kategori</th>
                        <th class="py-3 px-2.5 w-[13%]">Tingkat Kelas</th>
                        <th class="py-3 px-2.5 w-[15%]">Konten & Fitur</th>
                        <th class="py-3 px-2.5 w-[11%]">Status</th>
                        <th class="py-3 px-3 w-[14%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($materis as $index => $materi)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-[#EAF5EF] text-[#4EA87D] flex items-center justify-center font-bold text-xs shrink-0 border border-[#D7EDE1]">
                                        {{ str_pad($materis->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-slate-800 text-xs truncate" title="{{ $materi->judul }}">{{ $materi->judul }}</p>
                                        <p class="text-[11px] text-slate-400 truncate mt-0.5" title="{{ $materi->deskripsi_singkat }}">{{ $materi->deskripsi_singkat ?: 'Tidak ada ringkasan' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-2.5 whitespace-nowrap">
                                <x-badge-kategori :kategori="$materi->kategori" size="text-[11px] px-2.5 py-0.5" />
                            </td>
                            <td class="py-3 px-2.5 whitespace-nowrap text-slate-700 font-semibold text-xs">
                                <span class="whitespace-nowrap">{{ $materi->tingkat_kelas === 'Semua Kelas' ? 'Semua Kelas' : 'Kelas ' . $materi->tingkat_kelas }}</span>
                            </td>
                            <td class="py-3 px-2.5 whitespace-nowrap">
                                <div class="flex flex-col gap-0.5 text-[11px]">
                                    <span class="whitespace-nowrap inline-flex items-center gap-1 font-semibold text-slate-700">
                                        <svg class="w-3.5 h-3.5 text-[#4EA87D] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle></svg>
                                        {{ $materi->soals_count }} Soal Latihan
                                    </span>
                                    @if($materi->audio_materi)
                                        <span class="whitespace-nowrap inline-flex items-center gap-1 text-emerald-600 font-semibold">
                                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
                                            Audio MP3
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-2.5 whitespace-nowrap">
                                <x-badge-status :status="$materi->status" />
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Tombol Preview Siswa -->
                                    <x-button variant="show" href="{{ route('guru.materi.show', $materi->id) }}" title="Lihat Tampilan Siswa" />

                                    <!-- Tombol Edit -->
                                    <x-button variant="edit" href="{{ route('guru.materi.edit', $materi->id) }}" title="Edit Materi" />

                                    <!-- Tombol Hapus -->
                                    <x-button variant="delete" 
                                              type="button" 
                                              onclick="konfirmasiHapus('{{ route('guru.materi.destroy', $materi->id) }}', '{{ addslashes($materi->judul) }}', 'Modul materi ini beserta seluruh butir latihan soalnya akan dihapus secara permanen.')" 
                                              title="Hapus Materi" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-empty-state 
                            colspan="6" 
                            icon="📖" 
                            title="Belum ada modul materi pembelajaran." 
                            subtitle="Klik tombol + Tambah Materi Baru untuk membuat modul pembelajaran pertama."
                            actionText="Tambah Materi Baru"
                            :actionUrl="route('guru.materi.create')"
                        />
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
