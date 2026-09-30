<x-layout 
    title="Detail Kategori {{ $kategori->nama }} - Study English Prima" 
    pageTitle="Detail Kategori Materi" 
    pageSubtitle="Lihat ringkasan modul materi yang menggunakan kategori ini." 
    active="kategori"
>
    <!-- Navigasi Atas & Aksi -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('guru.kategori.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#1E6BFF] transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Daftar Kategori
        </a>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('guru.kategori.edit', $kategori->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs transition-all shadow-xs">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit Kategori
            </a>

            <!-- Form Hapus (Eksplisit POST) -->
            <form action="{{ route('guru.kategori.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-xs transition-all cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    @php
        $iconMap = [
            'auto_stories' => '📚',
            'spellcheck' => '✍️',
            'forum' => '🗣️',
            'translate' => '📖',
            'lightbulb' => '💡',
            'headphones' => '🎧',
            'business_center' => '💼',
            'track_changes' => '🎯',
        ];
        $emoji = $iconMap[$kategori->icon_name] ?? '🏷️';
    @endphp

    <!-- Kartu Informasi Utama Kategori -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <!-- Swatch Icon & Warna -->
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl shadow-sm border-2 border-white shrink-0" style="background-color: {{ $kategori->warna_hex }};">
                    <span>{{ $emoji }}</span>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-xl font-bold text-slate-800">{{ $kategori->nama }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase text-white shadow-xs" style="background-color: {{ $kategori->warna_hex }};">
                            {{ $kategori->slug }}
                        </span>
                        @if($kategori->is_aktif)
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold border border-emerald-200">
                                Aktif di Siswa
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 mt-1 font-mono">Kode Warna: {{ $kategori->warna_hex }} &bull; Identifier Ikon: {{ $kategori->icon_name }} &bull; Urutan: #{{ $kategori->urutan }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 bg-slate-50 px-5 py-3.5 rounded-2xl border border-slate-100">
                <div class="text-center">
                    <p class="text-[11px] text-slate-400 font-semibold uppercase">Total Materi</p>
                    <p class="text-lg font-black text-slate-800">{{ $kategori->materis->count() }}</p>
                </div>
            </div>
        </div>

        <div class="pt-5">
            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Pembelajaran</h4>
            <p class="text-xs text-slate-600 leading-relaxed max-w-4xl">
                {{ $kategori->deskripsi ?? 'Tidak ada deskripsi khusus untuk kategori ini.' }}
            </p>
        </div>
    </div>

    <!-- Bagian Daftar Modul Materi Terkait -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-base">Modul Materi Pembelajaran</h3>
                <p class="text-xs text-slate-400">Daftar semua materi yang dikelompokkan dalam kategori {{ $kategori->nama }}</p>
            </div>

            <a href="{{ route('guru.materi.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-[#1E6BFF] hover:bg-blue-600 text-white font-semibold text-xs transition-all shadow-xs">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Materi
            </a>
        </div>

        @if($kategori->materis->isEmpty())
            <div class="bg-white p-12 rounded-3xl border border-slate-100 shadow-xs text-center">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center mx-auto mb-3 text-xl">
                    📖
                </div>
                <h4 class="text-sm font-bold text-slate-800">Belum Ada Materi</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Kategori ini belum memiliki modul materi pembelajaran. Mulai buat modul pertama sekarang.</p>
                <a href="{{ route('guru.materi.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-2xl bg-[#1E6BFF] text-white text-xs font-bold hover:bg-blue-600 transition-all">
                    Buat Materi Baru
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($kategori->materis as $materi)
                    <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold text-white shadow-xs" style="background-color: {{ $kategori->warna_hex }};">
                                    {{ $materi->kategori }}
                                </span>
                                <span class="text-[11px] font-semibold text-slate-400">Kelas {{ $materi->kelas }} &bull; {{ $materi->level }}</span>
                            </div>

                            <h4 class="font-bold text-slate-800 text-sm group-hover:text-[#1E6BFF] transition-colors line-clamp-1">
                                {{ $materi->judul }}
                            </h4>

                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($materi->isi_materi), 100) }}
                            </p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                                <span>📝 {{ $materi->soals_count ?? 0 }} Soal</span>
                                <span>&bull;</span>
                                <span>⭐ +{{ $materi->xp_reward ?? 50 }} XP</span>
                            </div>

                            <a href="{{ route('guru.materi.show', $materi->id) }}" class="text-xs font-bold text-[#1E6BFF] hover:underline flex items-center gap-1">
                                Detail
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
