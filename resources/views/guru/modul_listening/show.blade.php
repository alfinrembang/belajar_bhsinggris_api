<x-layout 
    title="{{ $listening->judul }} - Detail Modul Listening" 
    pageTitle="Detail Modul Audio Listening" 
    pageSubtitle="Pratinjau modul audio listening, sesi rekaman, dan butir latihan soal siswa." 
    active="listening"
>
    <!-- Header Breadcrumb & Actions -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-button variant="back" href="{{ route('guru.listening.index') }}">
            Kembali ke Daftar Modul Listening
        </x-button>
        <div class="flex items-center gap-2 self-start sm:self-auto">
            <x-button variant="edit" href="{{ route('guru.listening.edit', $listening->id) }}">
                Edit Modul
            </x-button>
        </div>
    </div>

    <!-- 1. KARTU INFORMASI UTAMA & PLAYER TRACK -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs mb-6 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Deskripsi & Info Modul -->
            <div class="lg:col-span-2 space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-xl text-xs font-bold {{ $listening->tingkat_kesulitan === 'Beginner' ? 'bg-emerald-100 text-emerald-700' : ($listening->tingkat_kesulitan === 'Intermediate' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                        Level: {{ $listening->tingkat_kesulitan }}
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-semibold bg-[#EAF5EF] text-[#4EA87D]">
                        Target: {{ $listening->tingkat_kelas }}
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-semibold bg-purple-50 text-purple-700">
                        Total Soal: {{ $listening->soals->count() }} Pertanyaan
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700">
                        🎁 +{{ $listening->xp_reward }} XP
                    </span>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed">
                    {{ $listening->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}
                </p>
            </div>

            <!-- Sasaran Belajar (Checklist Langkah 1 Siswa) -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                <h4 class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                    <span>🎯</span> Sasaran Modul (Step 1)
                </h4>
                @if(!empty($listening->tujuan_belajar) && is_array($listening->tujuan_belajar))
                    <ul class="space-y-1.5">
                        @foreach($listening->tujuan_belajar as $pt)
                            <li class="flex items-start gap-2 text-[11px] text-slate-600">
                                <span class="text-emerald-500 font-bold">✓</span>
                                <span>{{ $pt }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-[11px] text-slate-400 italic">Sasaran standar pemahaman audio.</p>
                @endif
            </div>
        </div>

        <!-- Daftar Audio Tracks (Sesi Percakapan) -->
        <div class="pt-4 border-t border-slate-100 space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <span>🎧</span> Rekaman Audio Percakapan ({{ $listening->audios->count() }} Sesi Audio)
                </h3>
                <span class="text-[11px] text-slate-400">Digunakan untuk menjawab butir soal di bawah</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($listening->audios as $audio)
                    @php
                        $soalNumbers = $listening->soals->where('listening_audio_id', $audio->id)->pluck('nomor_soal')->sort()->values()->all();
                        if (empty($soalNumbers)) {
                            $rentangTeks = 'Belum dikaitkan ke soal';
                        } else {
                            $ranges = [];
                            $start = $soalNumbers[0];
                            $prev = $start;
                            for ($i = 1; $i < count($soalNumbers); $i++) {
                                $curr = $soalNumbers[$i];
                                if ($curr === $prev + 1) {
                                    $prev = $curr;
                                } else {
                                    $ranges[] = ($start === $prev) ? "{$start}" : "{$start}-{$prev}";
                                    $start = $curr;
                                    $prev = $curr;
                                }
                            }
                            $ranges[] = ($start === $prev) ? "{$start}" : "{$start}-{$prev}";
                            $rentangTeks = 'Digunakan untuk Soal No. ' . implode(', ', $ranges);
                        }
                    @endphp
                    <div class="p-4 rounded-2xl bg-gradient-to-r from-blue-50/60 via-purple-50/40 to-slate-50 border border-slate-200/80 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-lg bg-purple-600 text-white font-bold text-[10px]">
                                    Track #{{ $audio->nomor_track }}
                                </span>
                                <span class="text-xs font-bold text-slate-800">
                                    {{ $audio->judul_audio ?: 'Audio Track #'.$audio->nomor_track }}
                                </span>
                            </div>
                            <span class="text-[11px] font-mono text-slate-500 font-semibold">
                                {{ $audio->durasi ?: '02:00' }}
                            </span>
                        </div>

                        <audio controls class="w-full h-8" src="{{ $audio->audio_url }}"></audio>

                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-[#4EA87D] font-semibold flex items-center gap-1">
                                <span>📝</span> {{ $rentangTeks }}
                            </span>
                        </div>
                    </div>
                @empty
                    <!-- Fallback audio tunggal jika belum migrasi -->
                    <div class="p-4 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] space-y-2">
                        <span class="text-xs font-bold text-slate-800">Audio Utama</span>
                        <audio controls class="w-full h-8" src="{{ $listening->audio_url }}"></audio>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 2. BUTIR LATIHAN SOAL PEMAHAMAN -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-800 text-base">Butir Latihan Soal Pemahaman</h3>
                <p class="text-xs text-slate-400">Setiap soal merujuk ke audio track yang ditentukan oleh guru</p>
            </div>
            <span class="text-xs font-bold text-slate-600">Total: {{ $listening->soals->count() }} Pertanyaan</span>
        </div>

        @if($listening->soals->isEmpty())
            <div class="py-10 text-center text-slate-400">
                <p class="text-sm font-semibold text-slate-700">Belum Ada Soal Latihan</p>
                <p class="text-xs text-slate-400 mt-1">Tambahkan pertanyaan pemahaman untuk menguji listening siswa.</p>
                <a href="{{ route('guru.listening.edit', $listening->id) }}" class="inline-flex items-center gap-1.5 mt-3 text-xs font-bold text-[#4EA87D] hover:underline">
                    + Tambah Soal via Edit Modul
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($listening->soals as $soal)
                    @php
                        $trackNo = $soal->listeningAudio?->nomor_track ?? 1;
                        $trackJudul = $soal->listeningAudio?->judul_audio ?: 'Audio Track #'.$trackNo;
                    @endphp
                    <div class="p-5 rounded-2xl border border-slate-200/80 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-xl bg-[#4EA87D] text-white font-bold text-xs">
                                    Pertanyaan #{{ $soal->nomor_soal }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-lg bg-purple-100 text-purple-700 font-bold text-[11px] flex items-center gap-1">
                                    <span>🎧</span> Menggunakan: Track #{{ $trackNo }} ({{ $trackJudul }})
                                </span>
                            </div>
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
                            <div class="mt-2 p-3 rounded-xl bg-[#EAF5EF]/60 border border-[#D7EDE1] text-[11px] text-blue-900 leading-relaxed">
                                <strong class="font-bold">Pembahasan:</strong> {{ $soal->pembahasan }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
