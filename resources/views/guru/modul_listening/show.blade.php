<x-layout 
    title="Detail Modul Listening: {{ $listening->judul }} - Study English Prima" 
    pageTitle="Detail Modul Listening" 
    pageSubtitle="Pratinjau audio percakapan, transkrip naskah, dan butir latihan soal pemahaman." 
    active="listening"
>
    <!-- Navigasi Atas & Tombol Aksi -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('guru.listening.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#1E6BFF] transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Daftar Listening
        </a>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('guru.listening.edit', $listening->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs transition-all shadow-xs">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit Modul
            </a>

            <!-- Form Hapus (Eksplisit POST) -->
            <form action="{{ route('guru.listening.destroy', $listening->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket modul listening ini?');" class="inline">
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
        $levelBadge = match($listening->tingkat_kesulitan) {
            'Intermediate' => 'bg-purple-50 text-purple-600 border border-purple-200/80',
            'Advanced' => 'bg-rose-50 text-rose-600 border border-rose-200/80',
            default => 'bg-blue-50 text-[#1E6BFF] border border-blue-200/80',
        };
    @endphp

    <!-- 1. KARTU INFORMASI UTAMA & AUDIO PLAYER -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs mb-6 space-y-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-5 pb-6 border-b border-slate-100">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-2xl shrink-0 shadow-xs">
                    🎧
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-1.5">
                        <span class="px-3 py-1 rounded-full text-[11px] font-semibold {{ $levelBadge }}">
                            {{ $listening->tingkat_kesulitan }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[11px] font-semibold">
                            Kelas: {{ $listening->tingkat_kelas }}
                        </span>
                        @if($listening->status === 'aktif')
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-bold border border-emerald-200">
                                Aktif Tayang
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[11px] font-semibold">
                                Draft
                            </span>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $listening->judul }}</h2>
                    <p class="text-xs text-slate-500 mt-1 max-w-3xl leading-relaxed">
                        {{ $listening->deskripsi ?? 'Tidak ada ringkasan deskripsi.' }}
                    </p>
                </div>
            </div>

            <!-- Kartu Statistik Cepat Modul -->
            <div class="flex items-center gap-4 bg-slate-50 px-5 py-3 rounded-2xl border border-slate-100 self-start shrink-0">
                <div class="text-center">
                    <p class="text-[10px] text-slate-400 font-semibold uppercase">Durasi</p>
                    <p class="text-sm font-bold text-slate-800 font-mono">{{ $listening->durasi ?? '00:00' }}</p>
                </div>
                <div class="w-px h-7 bg-slate-200"></div>
                <div class="text-center">
                    <p class="text-[10px] text-slate-400 font-semibold uppercase">Latihan Soal</p>
                    <p class="text-sm font-bold text-slate-800">{{ $listening->soals->count() }} Soal</p>
                </div>
                <div class="w-px h-7 bg-slate-200"></div>
                <div class="text-center">
                    <p class="text-[10px] text-slate-400 font-semibold uppercase">Hadiah XP</p>
                    <p class="text-sm font-bold text-amber-600">+{{ $listening->xp_reward }}</p>
                </div>
            </div>
        </div>

        <!-- Pemutar Audio Player -->
        <div class="p-5 rounded-2xl bg-gradient-to-r from-blue-50/70 via-indigo-50/40 to-slate-50 border border-blue-100 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#1E6BFF] animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-800">
                        {{ $listening->audio_title ?? 'Audio Percakapan Listening' }}
                    </span>
                </div>
                <span class="text-[11px] font-mono text-slate-500 font-semibold">
                    Durasi: {{ $listening->durasi ?? '02:15' }}
                </span>
            </div>

            @if($listening->audio_url)
                <audio controls class="w-full h-10" src="{{ $listening->audio_url }}"></audio>
            @else
                <div class="py-3 text-center text-xs text-slate-400 italic">
                    Belum ada file audio yang diunggah untuk track ini.
                </div>
            @endif
        </div>
    </div>

    <!-- 2. TRANSKRIP & SASARAN BELAJAR -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Transkrip Naskah Percakapan -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-100 shadow-xs space-y-3">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <span>📜</span> Transkrip Naskah Percakapan
                </h3>
                <span class="text-[11px] text-slate-400">Dialog Bahasa Inggris</span>
            </div>

            @if($listening->transkrip)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 font-mono text-xs text-slate-800 leading-relaxed whitespace-pre-line max-h-72 overflow-y-auto">
                    {{ $listening->transkrip }}
                </div>
            @else
                <p class="text-xs text-slate-400 italic py-4">Belum ada naskah transkrip yang dimasukkan.</p>
            @endif
        </div>

        <!-- Sasaran Pembelajaran (Step 1 Checklist) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs space-y-3">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <span>🎯</span> Sasaran Modul
                </h3>
                <span class="text-[11px] text-slate-400">Step 1 Siswa</span>
            </div>

            @if(!empty($listening->tujuan_belajar) && is_array($listening->tujuan_belajar))
                <ul class="space-y-2.5">
                    @foreach($listening->tujuan_belajar as $point)
                        <li class="flex items-start gap-2.5 text-xs text-slate-700">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                                ✓
                            </span>
                            <span class="leading-relaxed">{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-xs text-slate-400 italic py-4">Tidak ada sasaran belajar khusus.</p>
            @endif
        </div>
    </div>

    <!-- 3. DAFTAR BUTIR PERTANYAAN (STEP 2 - 5) -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-wrap gap-2">
            <div>
                <h3 class="font-bold text-slate-800 text-base">Butir Latihan Soal Pemahaman Listening</h3>
                <p class="text-xs text-slate-400">Pertanyaan pilihan ganda yang dijawab siswa saat mendengarkan audio</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-blue-50 text-[#1E6BFF] text-xs font-bold">
                {{ $listening->soals->count() }} Butir Soal
            </span>
        </div>

        @if($listening->soals->isEmpty())
            <div class="py-10 text-center text-slate-400">
                <p class="text-sm font-semibold text-slate-700">Belum Ada Soal Latihan</p>
                <p class="text-xs text-slate-400 mt-1">Tambahkan pertanyaan pemahaman untuk menguji listening siswa.</p>
                <a href="{{ route('guru.listening.edit', $listening->id) }}" class="inline-flex items-center gap-1.5 mt-3 text-xs font-bold text-[#1E6BFF] hover:underline">
                    + Tambah Soal via Edit Modul
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($listening->soals as $soal)
                    <div class="p-5 rounded-2xl border border-slate-200/80 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-xl bg-slate-200 text-slate-700 font-bold text-xs">
                                Pertanyaan #{{ $soal->nomor_soal }}
                            </span>
                            <span class="text-xs font-semibold text-slate-400">Kunci: <strong class="text-emerald-600 font-bold text-sm">{{ $soal->jawaban_benar }}</strong></span>
                        </div>

                        <h4 class="text-sm font-bold text-slate-800 leading-snug">
                            {{ $soal->pertanyaan }}
                        </h4>

                        <!-- Grid Opsi Pilihan A - D -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                            @foreach(['A' => $soal->pilihan_a, 'B' => $soal->pilihan_b, 'C' => $soal->pilihan_c, 'D' => $soal->pilihan_d] as $key => $opt)
                                @php
                                    $isCorrect = ($key === $soal->jawaban_benar);
                                @endphp
                                <div class="flex items-center gap-2.5 p-3 rounded-xl border text-xs {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-800 font-semibold' : 'bg-white border-slate-200 text-slate-700' }}">
                                    <span class="w-6 h-6 rounded-lg flex items-center justify-center font-bold text-xs shrink-0 {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $key }}
                                    </span>
                                    <span class="flex-1">{{ $opt }}</span>
                                    @if($isCorrect)
                                        <span class="text-emerald-600 text-sm font-bold">✓ Benar</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($soal->pembahasan)
                            <div class="mt-2 p-3 rounded-xl bg-blue-50/60 border border-blue-100 text-[11px] text-blue-900 leading-relaxed">
                                <strong class="font-bold">Pembahasan:</strong> {{ $soal->pembahasan }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
