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
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
            </div>

            @if(request()->filled('q'))
                <a href="{{ route('guru.kategori.index') }}" class="text-xs text-rose-600 hover:underline font-semibold py-2">Reset</a>
            @endif
        </form>

        <!-- Tombol Tambah Kategori Baru -->
        <a href="{{ route('guru.kategori.create') }}" 
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-blue-600 text-white font-semibold text-xs transition-all shadow-[0_4px_12px_rgba(30,107,255,0.25)] shrink-0">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Kategori Baru</span>
        </a>
    </div>

    <!-- Tabel Daftar Kategori Materi -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6">Kategori & Visual</th>
                        <th class="py-4 px-6">Ikon</th>
                        <th class="py-4 px-6">Deskripsi Pembelajaran</th>
                        <th class="py-4 px-6">Jumlah Modul</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kategoris as $kategori)
                        @php
                            $iconEmojis = [
                                'auto_stories' => '📚',
                                'spellcheck' => '✍️',
                                'forum' => '🗣️',
                                'translate' => '📖',
                                'lightbulb' => '💡',
                                'headphones' => '🎧',
                                'business_center' => '💼',
                                'track_changes' => '🎯',
                            ];
                            $emoji = $iconEmojis[$kategori->icon_name] ?? '🏷️';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-xs shrink-0" style="background-color: {{ $kategori->warna_hex }};">
                                        {{ $emoji }}
                                    </div>
                                    <div>
                                        <a href="{{ route('guru.kategori.show', $kategori->id) }}" class="font-bold text-slate-800 hover:text-[#1E6BFF] text-sm transition-colors">
                                            {{ $kategori->nama }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $kategori->warna_hex }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-mono text-slate-600 text-[11px] font-semibold">
                                    {{ $kategori->icon_name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 max-w-xs">
                                <p class="text-slate-600 text-[11px] line-clamp-2">
                                    {{ $kategori->deskripsi ?: 'Belum ada deskripsi.' }}
                                </p>
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('guru.kategori.show', $kategori->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-[#1E6BFF] font-semibold text-[11px] hover:bg-blue-100 transition-colors">
                                    📘 {{ $kategori->materis_count }} Materi
                                </a>
                            </td>
                            <td class="py-4 px-6">
                                @if($kategori->is_aktif)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 font-semibold text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-400 font-semibold text-[10px]">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('guru.kategori.show', $kategori->id) }}" class="p-2 rounded-xl text-slate-400 hover:text-[#1E6BFF] hover:bg-blue-50 transition-colors" title="Lihat Detail & Materi">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>

                                    <a href="{{ route('guru.kategori.edit', $kategori->id) }}" class="p-2 rounded-xl text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit Kategori">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    <!-- Form Hapus (Eksplisit POST ke route destroy) -->
                                    <form action="{{ route('guru.kategori.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Kategori">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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

        @if($kategoris->hasPages())
            <div class="p-5 border-t border-slate-100">
                {{ $kategoris->links() }}
            </div>
        @endif
    </div>
</x-layout>
