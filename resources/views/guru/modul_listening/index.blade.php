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

    <!-- Tab Navigasi Sub-Modul Modul Listening -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 bg-white p-3 rounded-2xl border border-slate-100 shadow-xs">
        <div class="flex items-center gap-1.5 flex-wrap">
            <a href="{{ route('guru.listening.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-[#4EA87D] text-white shadow-xs">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                </svg>
                <span>Daftar Modul Rekaman</span>
            </a>
            <a href="{{ route('guru.laporan.listening') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span>Rekap Nilai Listening</span>
            </a>
        </div>

        <span class="text-xs font-semibold text-slate-500 px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl">
            Modul Listening Audio
        </span>
    </div>

    <!-- Bar Aksi Atas: Pencarian, Filter & Tombol Tambah Audio -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs mb-6">
        <!-- Form Filter & Pencarian -->
        <form method="GET" action="{{ route('guru.listening.index') }}" class="flex flex-wrap items-center gap-2.5 sm:gap-3 flex-1 min-w-0">
            <!-- Input Search -->
            <div class="relative w-full sm:w-64 lg:w-72">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari topik atau audio percakapan..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
            </div>

            <!-- Filter Rombel Kelas Terpadu -->
            <select name="rombel" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Kelas & Rombel</option>
                @if(isset($daftarRombelGrouped) && !empty($daftarRombelGrouped))
                    <option value="umum" {{ request('rombel') === 'umum' || request('rombel') === 'Semua Kelas' ? 'selected' : '' }}>
                        🌐 Umum (Semua Tingkat)
                    </option>
                    @foreach($daftarRombelGrouped as $tingkat => $rombelList)
                        <optgroup label="Tingkat Kelas {{ $tingkat }}">
                            <option value="tingkat:{{ $tingkat }}" {{ request('rombel') === 'tingkat:'.$tingkat || request('kelas') === (string)$tingkat ? 'selected' : '' }}>
                                📁 Semua Kelas {{ $tingkat }}
                            </option>
                            @foreach($rombelList as $rItem)
                                <option value="{{ $rItem }}" {{ request('rombel') === $rItem ? 'selected' : '' }}>
                                    {{ $rItem }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @endif
            </select>

            @if(request()->hasAny(['q', 'tingkat', 'rombel', 'kelas']))
                <a href="{{ route('guru.listening.index') }}" class="text-xs text-rose-600 hover:underline font-semibold py-2">Reset</a>
            @endif
        </form>

        <!-- Tombol Tambah Audio Listening Baru -->
        <div class="shrink-0 self-start lg:self-auto">
            <x-button variant="create" href="{{ route('guru.listening.create') }}" class="whitespace-nowrap shrink-0">
                Tambah Audio Baru
            </x-button>
        </div>
    </div>

    <!-- Ringkasan Statistik Listening -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5 mb-6">
        <x-stat-card title="Total Track Audio" :value="$totalAudio . ' Paket'" icon="headphones" color="green" />
        <x-stat-card title="Track Aktif Tayang" :value="$totalAktif . ' Track'" icon="check" color="emerald" />
        <x-stat-card title="Total Latihan Soal" :value="$totalSoal . ' Pertanyaan'" icon="file-text" color="green" />
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
                        <th class="py-3 px-3.5 w-10 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <input type="checkbox" 
                                       id="selectAllCheckbox" 
                                       onchange="togglePilihSemua(this)" 
                                       title="Centang untuk memilih seluruh modul listening di halaman ini"
                                       class="w-4 h-4 rounded text-[#4EA87D] focus:ring-[#4EA87D]/20 border-slate-300 cursor-pointer transition-all">
                            </div>
                        </th>
                        <th class="py-3 px-4 w-[42%]">Judul Percakapan & Audio</th>
                        <th class="py-3 px-2.5 w-[13%]">Durasi</th>
                        <th class="py-3 px-2.5 w-[14%]">Latihan Soal</th>
                        <th class="py-3 px-2.5 w-[12%]">Status</th>
                        <th class="py-3 px-3 w-[16%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($listenings as $item)
                        @php
                            $iconBg = 'bg-[#EAF5EF] text-[#2E7352]';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors" id="row-listening-{{ $item->id }}">
                            <td class="py-3.5 px-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" 
                                           name="listening_ids[]" 
                                           value="{{ $item->id }}" 
                                           data-judul="{{ $item->judul }}"
                                           onchange="onItemCheckboxChange()"
                                           class="listening-item-checkbox w-4 h-4 rounded text-[#4EA87D] focus:ring-[#4EA87D]/20 border-slate-300 cursor-pointer transition-all">
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-lg {{ $iconBg }} flex items-center justify-center shrink-0 border border-slate-200/60">
                                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                                            <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <a href="{{ route('guru.listening.show', $item->id) }}" class="font-bold text-slate-800 hover:text-[#4EA87D] transition-colors truncate block text-xs" title="{{ $item->judul }}">
                                                {{ $item->judul }}
                                            </a>
                                            @if(!empty($item->target_rombel_list))
                                                @foreach($item->target_rombel_list as $rBadge)
                                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200/60 shrink-0">{{ $rBadge }}</span>
                                                @endforeach
                                            @elseif($item->tingkat_kelas && $item->tingkat_kelas !== 'Semua Kelas')
                                                <span class="text-[10px] font-semibold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200/60 shrink-0">Kelas {{ str_replace('Kelas ', '', $item->tingkat_kelas) }}</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-slate-400 truncate mt-0.5" title="{{ $item->deskripsi }}">
                                            {{ $item->deskripsi ?? 'Tidak ada ringkasan deskripsi' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-2.5 text-slate-600 font-mono text-[11.5px] whitespace-nowrap">
                                <div class="whitespace-nowrap">{{ $item->durasi ?? '00:00' }}</div>
                                @if(isset($item->audios_count) && $item->audios_count > 1)
                                    <span class="text-[10px] text-[#2E7352] font-bold bg-[#EAF5EF] px-1.5 py-0.5 rounded-md whitespace-nowrap">{{ $item->audios_count }} Track</span>
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
                            actionText="Tambah Audio Baru"
                            :actionUrl="route('guru.listening.create')"
                        />
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($listenings->total() > 0)
            <div class="px-6 py-3.5 border-t border-slate-100">
                <x-pagination :paginator="$listenings" />
            </div>
        @endif
    </div>

    <!-- Floating Bar Aksi Massal & Modal Hapus Listening -->
    <x-bulk-delete-bar 
        :action="route('guru.listening.bulk_destroy')"
        checkboxClass="listening-item-checkbox"
        inputName="listening_ids[]"
        itemLabel="modul listening"
        :totalPageCount="$listenings->count()"
        warningText="Seluruh rekaman audio, butir latihan soal listening, dan riwayat progres siswa pada modul terpilih akan dihapus permanen."
    />
</x-layout>
