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
        <x-button variant="create" href="{{ route('guru.listening.create') }}">
            Unggah Audio Baru
        </x-button>
    </div>

    <!-- Ringkasan Statistik Listening -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5 mb-6">
        <x-stat-card title="Total Track Audio" :value="$totalAudio . ' Paket'" icon="headphones" color="blue" />
        <x-stat-card title="Track Aktif Tayang" :value="$totalAktif . ' Track'" icon="check" color="emerald" />
        <x-stat-card title="Total Latihan Soal" :value="$totalSoal . ' Pertanyaan'" icon="file-text" color="purple" />
    </div>

    <!-- Tabel Daftar Track Listening -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Daftar Modul Rekaman Audio</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Sinkron otomatis dengan menu listening aplikasi siswa</p>
            </div>
            <span class="text-xs text-slate-500 font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200/60">Total: {{ $listenings->total() }} Paket</span>
        </div>

        <div class="w-full overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/70 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4 w-[36%]">Judul Percakapan & Audio</th>
                        <th class="py-3 px-2.5 w-[13%]">Tingkat</th>
                        <th class="py-3 px-2.5 w-[11%]">Durasi</th>
                        <th class="py-3 px-2.5 w-[13%]">Latihan Soal</th>
                        <th class="py-3 px-2.5 w-[11%]">Status</th>
                        <th class="py-3 px-3 w-[16%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($listenings as $item)
                        @php
                            $levelColor = match($item->tingkat_kesulitan) {
                                'Intermediate' => 'bg-purple-50 text-purple-700 border-purple-100',
                                'Advanced' => 'bg-rose-50 text-rose-700 border-rose-100',
                                default => 'bg-blue-50 text-[#1E6BFF] border-blue-100',
                            };
                            $iconBg = match($item->tingkat_kesulitan) {
                                'Intermediate' => 'bg-purple-50 text-purple-600',
                                'Advanced' => 'bg-rose-50 text-rose-600',
                                default => 'bg-blue-50 text-[#1E6BFF]',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-lg {{ $iconBg }} flex items-center justify-center shrink-0 border border-slate-200/60">
                                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                                            <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('guru.listening.show', $item->id) }}" class="font-bold text-slate-800 hover:text-blue-600 transition-colors truncate block text-xs" title="{{ $item->judul }}">
                                            {{ $item->judul }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 truncate mt-0.5" title="{{ $item->deskripsi }}">
                                            {{ $item->deskripsi ?? 'Tidak ada ringkasan deskripsi' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-2.5 whitespace-nowrap">
                                <span class="whitespace-nowrap px-2.5 py-0.5 rounded-md text-[10.5px] font-semibold border {{ $levelColor }} inline-flex items-center">
                                    {{ $item->tingkat_kesulitan }}
                                </span>
                            </td>
                            <td class="py-3 px-2.5 text-slate-600 font-mono text-[11.5px] whitespace-nowrap">
                                <div class="whitespace-nowrap">{{ $item->durasi ?? '00:00' }}</div>
                                @if(isset($item->audios_count) && $item->audios_count > 1)
                                    <span class="text-[10px] text-purple-600 font-bold bg-purple-50 px-1.5 py-0.5 rounded-md whitespace-nowrap">{{ $item->audios_count }} Track</span>
                                @endif
                            </td>
                            <td class="py-3 px-2.5 whitespace-nowrap">
                                <span class="whitespace-nowrap inline-flex items-center gap-1.5 text-slate-700 font-semibold text-[11.5px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $item->soals_count }} Soal
                                </span>
                            </td>
                            <td class="py-3 px-2.5 whitespace-nowrap">
                                <x-badge-status :status="$item->status" />
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1">
                                    <x-button variant="show" href="{{ route('guru.listening.show', $item->id) }}" title="Lihat Detail Audio & Soal" />
                                    <x-button variant="edit" href="{{ route('guru.listening.edit', $item->id) }}" title="Edit Modul" />
                                    <!-- Tombol Hapus -->
                                    <x-button variant="delete" 
                                              type="button" 
                                              onclick="konfirmasiHapus('{{ route('guru.listening.destroy', $item->id) }}', '{{ addslashes($item->judul) }}', 'Paket modul listening dan seluruh rekaman audio terkait akan dihapus secara permanen.')" 
                                              title="Hapus Modul" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-empty-state 
                            colspan="6" 
                            icon="🎧" 
                            title="Belum Ada Modul Listening" 
                            subtitle="Unggah audio rekaman percakapan pertama untuk pembelajaran listening siswa."
                            actionText="Unggah Audio Baru"
                            :actionUrl="route('guru.listening.create')"
                        />
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
