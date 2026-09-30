<x-layout 
    title="Detail Materi - {{ $materi->judul }}" 
    pageTitle="Detail Modul Materi" 
    pageSubtitle="Pratinjau tampilan materi yang akan dilihat dan dipelajari oleh siswa di aplikasi Flutter." 
    active="materi"
>
    <!-- Navigasi Kembali & Tombol Tindakan -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('guru.materi.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#1E6BFF] transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Daftar Materi
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('guru.materi.edit', $materi->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50 text-[#1E6BFF] hover:bg-blue-100 font-semibold text-xs transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit Materi Ini
            </a>

            <form action="{{ route('guru.materi.destroy', $materi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');" class="inline">
                @csrf
                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Materi">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Header Informasi Materi -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="px-3 py-1 rounded-full bg-blue-50 text-[#1E6BFF] text-xs font-semibold">
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
            <span class="p-2 rounded-xl bg-blue-50 text-[#1E6BFF] text-base">📖</span>
            <h3 class="font-bold text-slate-800 text-sm">Penjelasan Teori & Konsep Belajar</h3>
        </div>
        <div class="text-xs text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
            {{ $materi->penjelasan }}
        </div>
    </div>

    <!-- KONTEN 2: CONTOH TEKS, GAMBAR, DAN AUDIO LISTENING -->
    @if($materi->contoh_teks || $materi->audio_materi || $materi->gambar_materi)
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 text-base">🎧</span>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Contoh Penerapan & Audio Listening</h3>
                    <p class="text-[11px] text-slate-400">Dapat didengar oleh siswa saat membaca contoh teks</p>
                </div>
            </div>

            <!-- Gambar Cover / Ilustrasi jika ada -->
            @if($materi->gambar_materi)
                <div class="rounded-2xl overflow-hidden max-h-72 border border-slate-200">
                    <img src="{{ $materi->gambar_url }}" alt="Cover Materi" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Contoh Teks Bacaan -->
            @if($materi->contoh_teks)
                <div class="p-5 rounded-2xl bg-emerald-50/40 border border-emerald-100 text-xs text-slate-800 leading-relaxed italic whitespace-pre-line">
                    "{{ $materi->contoh_teks }}"
                </div>
            @endif

            <!-- Pemutar Audio MP3 jika ada -->
            @if($materi->audio_materi)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-base">
                            ▶
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Audio Pelafalan Contoh Teks</p>
                            <p class="text-[11px] text-slate-400">Dengarkan cara pengucapan native speaker</p>
                        </div>
                    </div>
                    <audio controls src="{{ $materi->audio_url }}" class="w-full sm:w-72 h-9"></audio>
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
                        <span class="px-2.5 py-0.5 rounded-full {{ $soal->tipe_soal === 'listening' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-[#1E6BFF]' }} text-[11px] font-semibold">
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
                        <div class="p-3 rounded-xl bg-blue-50/50 border border-blue-100 text-[11px] text-blue-900">
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
