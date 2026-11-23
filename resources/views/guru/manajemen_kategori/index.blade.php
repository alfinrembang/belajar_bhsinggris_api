<x-layout 
    title="Kategori Materi - Study English Prima" 
    pageTitle="Kategori Materi Belajar" 
    pageSubtitle="Kelola master kategori materi, tema warna visual, dan ikon untuk tampilan aplikasi siswa." 
    active="kategori"
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

    <!-- Bar Aksi Atas: Pencarian & Tombol Tambah Kategori -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs mb-6">
        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('guru.kategori.index') }}" class="flex items-center gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-80">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari nama atau deskripsi kategori..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
            </div>

            @if(request()->filled('q'))
                <a href="{{ route('guru.kategori.index') }}" class="text-xs text-rose-600 hover:underline font-semibold py-2">Reset</a>
            @endif
        </form>

        <!-- Tombol Tambah Kategori Baru -->
        <x-button variant="create" href="{{ route('guru.kategori.create') }}">
            Tambah Kategori Baru
        </x-button>
    </div>

    <!-- Tabel Daftar Kategori Materi -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] overflow-hidden">
        <div class="w-full overflow-hidden">
            <table class="w-full table-fixed text-left text-xs text-slate-600 border-collapse">
                <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-200/70">
                    <tr>
                        <th class="py-3 px-3.5 w-10 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <input type="checkbox" 
                                       id="selectAllCheckbox" 
                                       onchange="togglePilihSemua(this)" 
                                       title="Centang untuk memilih seluruh kategori di halaman ini"
                                       class="w-4 h-4 rounded text-[#4EA87D] focus:ring-[#4EA87D]/20 border-slate-300 cursor-pointer transition-all">
                            </div>
                        </th>
                        <th class="py-3 px-4 w-[22%] whitespace-nowrap">Kategori & Visual</th>
                        <th class="py-3 px-3 w-[11%] whitespace-nowrap">Ikon</th>
                        <th class="py-3 px-3 w-[30%] whitespace-nowrap">Deskripsi Pembelajaran</th>
                        <th class="py-3 px-3 w-[12%] whitespace-nowrap">Jumlah Modul</th>
                        <th class="py-3 px-3 w-[9%] whitespace-nowrap">Status</th>
                        <th class="py-3 px-3 w-[14%] text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kategoris as $kategori)
                        <tr class="hover:bg-slate-50/70 transition-colors" id="row-kategori-{{ $kategori->id }}">
                            <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" 
                                           name="kategori_ids[]" 
                                           value="{{ $kategori->id }}" 
                                           data-nama="{{ $kategori->nama }}"
                                           onchange="onItemCheckboxChange()"
                                           class="kategori-item-checkbox w-4 h-4 rounded text-[#4EA87D] focus:ring-[#4EA87D]/20 border-slate-300 cursor-pointer transition-all">
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-xs shrink-0" style="background-color: {{ $kategori->warna_hex ?: '#4EA87D' }};">
                                        @if($kategori->icon_name === 'auto_stories')
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                        @elseif($kategori->icon_name === 'spellcheck')
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 11 3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                        @elseif($kategori->icon_name === 'forum')
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                        @elseif($kategori->icon_name === 'translate')
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('guru.kategori.show', $kategori->id) }}" class="font-bold text-slate-800 hover:text-[#4EA87D] text-xs transition-colors">
                                            {{ $kategori->nama }}
                                        </a>
                                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $kategori->warna_hex ?: '#4EA87D' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200/70 text-slate-700 text-xs font-medium">
                                    @if($kategori->icon_name === 'auto_stories')
                                        <svg class="w-3.5 h-3.5 text-[#4EA87D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                        <span>Reading</span>
                                    @elseif($kategori->icon_name === 'spellcheck')
                                        <svg class="w-3.5 h-3.5 text-[#4EA87D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 11 3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                        <span>Grammar</span>
                                    @elseif($kategori->icon_name === 'forum')
                                        <svg class="w-3.5 h-3.5 text-[#4EA87D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                        <span>Conversation</span>
                                    @elseif($kategori->icon_name === 'translate')
                                        <svg class="w-3.5 h-3.5 text-[#4EA87D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                        <span>Vocabulary</span>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-[#4EA87D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path></svg>
                                        <span>{{ ucfirst($kategori->icon_name ?: 'General') }}</span>
                                    @endif
                                </span>
                            </td>
                            <td class="py-3 px-4 max-w-xs">
                                <p class="text-slate-600 text-[11px] line-clamp-2">
                                    {{ $kategori->deskripsi ?: 'Belum ada deskripsi.' }}
                                </p>
                            </td>
                            <td class="py-3 px-3.5 whitespace-nowrap">
                                <a href="{{ route('guru.kategori.show', $kategori->id) }}" class="whitespace-nowrap inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-[#EAF5EF] text-[#2E7352] font-semibold text-[11px] border border-[#D7EDE1] hover:bg-[#EAF5EF] transition-colors">
                                    <svg class="w-3 h-3 text-[#4EA87D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                    {{ $kategori->materis_count }} Modul
                                </a>
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                @if($kategori->is_aktif)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60 font-semibold text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 border border-slate-200/60 font-semibold text-[10px]">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <x-button variant="show" href="{{ route('guru.kategori.show', $kategori->id) }}" title="Lihat Detail & Materi" />

                                    <x-button variant="edit" href="{{ route('guru.kategori.edit', $kategori->id) }}" title="Edit Kategori" />

                                    <!-- Tombol Hapus -->
                                    <x-button variant="delete" 
                                              type="button" 
                                              onclick="konfirmasiHapus('{{ route('guru.kategori.destroy', $kategori->id) }}', 'Kategori {{ addslashes($kategori->nama) }}', 'Kategori ini akan dihapus dari sistem.')" 
                                              title="Hapus Kategori" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3 text-xl">
                                    🏷️
                                </div>
                                <p class="text-sm font-semibold text-slate-700">Belum Ada Kategori</p>
                                <p class="text-xs text-slate-400 mt-1">Tambahkan kategori materi pembelajaran pertama untuk memulai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kategoris->total() > 0)
            <div class="px-6 py-3.5 border-t border-slate-100">
                <x-pagination :paginator="$kategoris" />
            </div>
        @endif
    </div>

    <!-- Floating Bar Aksi Massal & Modal Hapus Kategori -->
    <x-bulk-delete-bar 
        :action="route('guru.kategori.bulk_destroy')"
        checkboxClass="kategori-item-checkbox"
        inputName="kategori_ids[]"
        itemLabel="kategori materi"
        :totalPageCount="$kategoris->count()"
        warningText="Kategori materi yang dipilih akan dihapus permanen. Pastikan kategori tidak sedang digunakan oleh modul materi."
    />
</x-layout>
