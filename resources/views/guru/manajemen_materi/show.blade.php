<x-layout 
    title="Detail Materi - {{ $materi->judul }}" 
    pageTitle="Detail Modul Materi" 
    pageSubtitle="Pratinjau tampilan materi yang akan dilihat dan dipelajari oleh siswa di aplikasi Flutter." 
    active="materi"
>
    <!-- Navigasi Kembali & Tombol Tindakan -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-button variant="back" href="{{ route('guru.materi.index') }}">
            Kembali ke Daftar Materi
        </x-button>

        <div class="flex items-center gap-2">
            <x-button variant="edit" href="{{ route('guru.materi.edit', $materi->id) }}">
                Edit Materi Ini
            </x-button>

            <x-button variant="delete" 
                      type="button" 
                      onclick="konfirmasiHapus('{{ route('guru.materi.destroy', $materi->id) }}', '{{ addslashes($materi->judul) }}', 'Materi ini beserta seluruh soal latihannya akan dihapus secara permanen.')" 
                      title="Hapus Materi" />
        </div>
    </div>

    <!-- Header Informasi Materi -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="px-3 py-1 rounded-full bg-[#EAF5EF] text-[#4EA87D] text-xs font-semibold">
                {{ $materi->kategori }}
            </span>
            <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-600 text-xs font-semibold">
                {{ $materi->tingkat_kelas === 'Semua Kelas' ? 'Semua Tingkat Kelas' : 'Khusus Kelas ' . $materi->tingkat_kelas }}
            </span>
            <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-semibold">
                ⚡ +{{ $materi->xp_reward }} XP Reward
            </span>
            @if($materi->status === 'aktif')
                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Aktif di Flutter
                </span>
            @else
                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold">
                    Draft (Belum Terbit)
                </span>
            @endif
        </div>

        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $materi->judul }}</h1>
            @if($materi->deskripsi_singkat)
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $materi->deskripsi_singkat }}</p>
            @endif
        </div>
    </div>

    <!-- KONTEN 1: PENJELASAN TEORI -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-4">
        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
            <span class="p-2 rounded-xl bg-[#EAF5EF] text-[#4EA87D] text-base">📖</span>
            <h3 class="font-bold text-slate-800 text-sm">Penjelasan Teori & Konsep Belajar</h3>
        </div>
        <div class="text-xs text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
            {{ $materi->penjelasan }}
        </div>
    </div>

    <!-- KONTEN 2: CONTOH TEKS / CERITA BACAAN & GAMBAR ILUSTRASI -->
    @if($materi->contoh_teks || $materi->gambar_materi)
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 text-base">📚</span>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Contoh Teks / Cerita Bacaan</h3>
                    <p class="text-[11px] text-slate-400">Contoh penerapan materi dalam teks nyata, cerita pendek, atau dialog percakapan</p>
                </div>
            </div>

            <!-- Gambar Ilustrasi jika ada -->
            @if($materi->gambar_materi)
                <div class="rounded-2xl overflow-hidden max-h-72 border border-slate-200">
                    <img src="{{ $materi->gambar_url }}" alt="Ilustrasi Cerita" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Contoh Teks Bacaan -->
            @if($materi->contoh_teks)
                <div class="p-5 rounded-2xl bg-emerald-50/40 border border-emerald-100 text-xs text-slate-800 leading-relaxed italic whitespace-pre-line">
                    "{{ $materi->contoh_teks }}"
                </div>
            @endif
        </div>
    @endif

    <!-- KONTEN 3: LATIHAN SOAL PEMAHAMAN (MINI QUIZ) -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <span class="p-2 rounded-xl bg-purple-50 text-purple-600 text-base">🎯</span>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Latihan Soal Pemahaman (Mini Quiz)</h3>
                    <p class="text-[11px] text-slate-400">Total: {{ $materi->soals->count() }} Butir Soal Terdaftar</p>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($materi->soals as $index => $soal)
                <div class="p-5 rounded-2xl border border-slate-200/80 bg-slate-50/40 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs">
                            Soal #{{ $index + 1 }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full {{ $soal->tipe_soal === 'listening' ? 'bg-purple-100 text-purple-700' : 'bg-[#EAF5EF] text-[#4EA87D]' }} text-[11px] font-semibold">
                            {{ $soal->tipe_soal === 'listening' ? '🎧 Soal Listening' : '📝 Pilihan Ganda' }}
                        </span>
                    </div>

                    <!-- Audio khusus listening jika ada -->
                    @if($soal->audio_soal)
                        <div class="p-2.5 rounded-xl bg-purple-50 border border-purple-100">
                            <p class="text-[11px] font-semibold text-purple-800 mb-1">Dengarkan audio pertanyaan:</p>
                            <audio controls src="{{ $soal->audio_soal_url }}" class="w-full h-8"></audio>
                        </div>
                    @endif

                    <!-- Pertanyaan -->
                    <p class="text-xs font-bold text-slate-800">{{ $soal->pertanyaan }}</p>

                    <!-- Pilihan Jawaban A, B, C, D -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <div class="p-2.5 rounded-xl border {{ $soal->kunci_jawaban === 'A' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-800' : 'bg-white border-slate-200 text-slate-700' }}">
                            <span class="font-bold mr-1">A.</span> {{ $soal->pilihan_a }}
                            @if($soal->kunci_jawaban === 'A') <span class="text-emerald-600 text-[10px] ml-1">✓ (Kunci)</span> @endif
                        </div>
                        <div class="p-2.5 rounded-xl border {{ $soal->kunci_jawaban === 'B' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-800' : 'bg-white border-slate-200 text-slate-700' }}">
                            <span class="font-bold mr-1">B.</span> {{ $soal->pilihan_b }}
                            @if($soal->kunci_jawaban === 'B') <span class="text-emerald-600 text-[10px] ml-1">✓ (Kunci)</span> @endif
                        </div>
                        <div class="p-2.5 rounded-xl border {{ $soal->kunci_jawaban === 'C' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-800' : 'bg-white border-slate-200 text-slate-700' }}">
                            <span class="font-bold mr-1">C.</span> {{ $soal->pilihan_c }}
                            @if($soal->kunci_jawaban === 'C') <span class="text-emerald-600 text-[10px] ml-1">✓ (Kunci)</span> @endif
                        </div>
                        <div class="p-2.5 rounded-xl border {{ $soal->kunci_jawaban === 'D' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-800' : 'bg-white border-slate-200 text-slate-700' }}">
                            <span class="font-bold mr-1">D.</span> {{ $soal->pilihan_d }}
                            @if($soal->kunci_jawaban === 'D') <span class="text-emerald-600 text-[10px] ml-1">✓ (Kunci)</span> @endif
                        </div>
                    </div>

                    @if($soal->pembahasan)
                        <div class="p-3 rounded-xl bg-[#EAF5EF]/50 border border-[#D7EDE1] text-[11px] text-blue-900">
                            <strong>Pembahasan:</strong> {{ $soal->pembahasan }}
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-center text-xs text-slate-400 py-6">Materi ini belum memiliki butir soal latihan pemahaman.</p>
            @endforelse
        </div>
    </div>
</x-layout>
