<x-layout 
    title="{{ $quiz->judul }} - Detail Kuis - Study English Prima" 
    pageTitle="Detail & Hasil Kuis" 
    pageSubtitle="Pratinjau butir pertanyaan, kunci jawaban, dan monitoring nilai siswa yang telah mengerjakan." 
    active="quiz"
>
    <!-- Navigasi Kembali & Aksi Edit -->
    <div class="flex items-center justify-between gap-4 mb-6">
        <x-button variant="back" href="{{ route('guru.quiz.index') }}">
            Kembali ke Bank Soal & Kuis
        </x-button>

        <a href="{{ route('guru.quiz.edit', $quiz) }}" 
           class="px-4 py-2 rounded-2xl bg-[#4EA87D] hover:bg-[#43966E] text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Edit Paket Kuis
        </a>
    </div>

    <!-- Banner Ringkasan Kuis & Kode Room -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div class="space-y-2">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-bold">
                        {{ $quiz->kategori }}
                    </span>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                        Kelas: {{ $quiz->tingkat_kelas }}
                    </span>
                    <x-badge-status :status="$quiz->status" />
                </div>

                <h2 class="text-xl lg:text-2xl font-bold text-slate-800 tracking-tight">
                    {{ $quiz->judul }}
                </h2>

                @if($quiz->deskripsi)
                    <p class="text-xs text-slate-500 max-w-3xl leading-relaxed">
                        {{ $quiz->deskripsi }}
                    </p>
                @endif
            </div>

            <!-- Kartu Kode Room Besar -->
            <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50/60 border border-[#D7EDE1] flex flex-col items-center justify-center shrink-0 min-w-[200px]">
                <span class="text-[11px] font-bold text-[#4EA87D] uppercase tracking-wider">KODE ROOM SISWA</span>
                <span class="text-3xl font-mono font-extrabold text-slate-900 tracking-widest mt-1">
                    {{ $quiz->kode_room }}
                </span>
                <button type="button" 
                        onclick="salinKode('{{ $quiz->kode_room }}', this)"
                        class="mt-2.5 px-3 py-1 rounded-lg bg-white shadow-xs border border-[#D7EDE1] text-[#4EA87D] text-[11px] font-bold hover:bg-[#EAF5EF] transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    <span>Salin Kode</span>
                </button>
            </div>
        </div>

        <!-- 4 Metrik Kuis -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[11px] font-bold text-slate-400 uppercase">Jumlah Soal</span>
                <p class="text-lg font-bold text-slate-800 mt-0.5">{{ $quiz->soals->count() }} Butir</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[11px] font-bold text-slate-400 uppercase">Durasi Pengerjaan</span>
                <p class="text-lg font-bold text-slate-800 mt-0.5">{{ $quiz->durasi_menit }} Menit</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[11px] font-bold text-slate-400 uppercase">Reward XP</span>
                <p class="text-lg font-bold text-amber-600 mt-0.5">+{{ $quiz->xp_reward }} XP</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[11px] font-bold text-slate-400 uppercase">Siswa Selesai</span>
                <p class="text-lg font-bold text-emerald-600 mt-0.5">{{ $quiz->progresSiswa->where('status', 'selesai')->count() }} Orang</p>
            </div>
        </div>
    </div>

    <!-- SEKSI TABEL BUTIR PERTANYAAN -->
    <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                    📝
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Daftar Butir Pertanyaan & Kunci Jawaban</h3>
                    <p class="text-xs text-slate-400">Total {{ $quiz->soals->count() }} butir soal evaluasi</p>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($quiz->soals as $soal)
                <div class="p-5 rounded-2xl border border-slate-200/80 bg-slate-50/30 space-y-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-xl bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center">
                                {{ $soal->nomor_soal }}
                            </span>
                            <span class="font-bold text-slate-800 text-xs">
                                @if($soal->tipe_soal === 'listening')
                                    🎧 Listening Question
                                @elseif($soal->tipe_soal === 'reading')
                                    📖 Reading Question
                                @else
                                    📝 Multiple Choice Question
                                @endif
                            </span>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-xs">
                            Kunci Jawaban: {{ $soal->kunci_jawaban }}
                        </span>
                    </div>

                    @if($soal->tipe_soal === 'reading' && $soal->reading_passage)
                        <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200/70 text-xs text-slate-800 font-serif leading-relaxed italic">
                            <span class="font-sans font-bold text-[11px] text-amber-900 not-italic block mb-1">📖 Teks Bacaan:</span>
                            {!! nl2br(e($soal->reading_passage)) !!}
                        </div>
                    @endif

                    @if($soal->tipe_soal === 'listening' && $soal->audio_file)
                        <div class="p-3 rounded-xl bg-purple-50/70 border border-purple-200/80 space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-purple-900">🎧 {{ $soal->audio_title ?: 'Audio Percakapan Listening' }}</span>
                                <span class="text-purple-600 font-medium text-[11px]">{{ $soal->audio_duration }}</span>
                            </div>
                            <audio controls class="w-full h-8">
                                <source src="{{ $soal->audio_url }}" type="audio/mpeg">
                            </audio>
                        </div>
                    @endif

                    <p class="text-xs font-semibold text-slate-800 pt-1">
                        {{ $soal->pertanyaan }}
                    </p>

                    <!-- Pilihan Jawaban -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <div class="p-2.5 rounded-xl border {{ $soal->kunci_jawaban === 'A' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900' : 'bg-white border-slate-200 text-slate-700' }} flex items-center gap-2">
                            <span class="w-5 text-slate-400">A.</span>
                            <span>{{ $soal->pilihan_a }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl border {{ $soal->kunci_jawaban === 'B' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900' : 'bg-white border-slate-200 text-slate-700' }} flex items-center gap-2">
                            <span class="w-5 text-slate-400">B.</span>
                            <span>{{ $soal->pilihan_b }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl border {{ $soal->kunci_jawaban === 'C' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900' : 'bg-white border-slate-200 text-slate-700' }} flex items-center gap-2">
                            <span class="w-5 text-slate-400">C.</span>
                            <span>{{ $soal->pilihan_c }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl border {{ $soal->kunci_jawaban === 'D' ? 'bg-emerald-50 border-emerald-300 font-bold text-emerald-900' : 'bg-white border-slate-200 text-slate-700' }} flex items-center gap-2">
                            <span class="w-5 text-slate-400">D.</span>
                            <span>{{ $soal->pilihan_d }}</span>
                        </div>
                    </div>

                    @if($soal->pembahasan)
                        <div class="p-2.5 rounded-xl bg-[#EAF5EF]/60 border border-[#D7EDE1] text-[11px] text-blue-900">
                            <span class="font-bold">💡 Pembahasan:</span> {{ $soal->pembahasan }}
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-center py-8 text-xs text-slate-400">Belum ada butir pertanyaan pada kuis ini.</p>
            @endforelse
        </div>
    </div>

    <!-- SEKSI MONITORING RIWAYAT NILAI SISWA -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Hasil & Peringkat Siswa</h3>
                <p class="text-xs text-slate-400">Riwayat pengerjaan siswa melalui aplikasi mobile</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-[#EAF5EF] text-[#4EA87D]">
                {{ $quiz->progresSiswa->count() }} Percobaan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Siswa</th>
                        <th class="py-3.5 px-6">Nilai Akhir</th>
                        <th class="py-3.5 px-6">Benar / Salah</th>
                        <th class="py-3.5 px-6">XP Reward</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6">Waktu Pengerjaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($quiz->progresSiswa as $progres)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ substr($progres->siswa->nama ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $progres->siswa->nama ?? 'Siswa' }}</p>
                                        <p class="text-[11px] text-slate-400">{{ $progres->siswa->email ?? '-' }} (Kelas {{ $progres->siswa->kelas ?? '-' }})</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-base font-extrabold {{ ($progres->skor ?? 0) >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $progres->skor ?? 0 }}
                                </span>
                                <span class="text-[11px] text-slate-400">/ 100</span>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                <span class="text-emerald-600 font-bold">{{ $progres->jumlah_benar ?? 0 }} Benar</span> • 
                                <span class="text-rose-600 font-bold">{{ max(0, ($progres->total_soal ?? 0) - ($progres->jumlah_benar ?? 0)) }} Salah</span>
                            </td>
                            <td class="py-4 px-6 text-amber-600 font-bold">
                                +{{ $progres->xp_didapat ?? 0 }} XP
                            </td>
                            <td class="py-4 px-6">
                                @if($progres->status === 'selesai')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-[11px] font-semibold">
                                        Mengerjakan
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-400 text-[11px]">
                                {{ $progres->completed_at ? $progres->completed_at->translatedFormat('d M Y, H:i') : ($progres->created_at ? $progres->created_at->translatedFormat('d M Y, H:i') : '-') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-3xl mb-2">🎯</span>
                                    <p class="font-bold text-slate-600 text-xs">Belum ada siswa yang mengerjakan kuis ini</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Bagikan kode room <span class="font-mono font-bold text-[#4EA87D]">{{ $quiz->kode_room }}</span> kepada siswa Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script Salin Kode -->
    <script>
        function salinKode(kode, btn) {
            navigator.clipboard.writeText(kode).then(() => {
                const span = btn.querySelector('span');
                if (span) {
                    const original = span.textContent;
                    span.textContent = 'Tersalin!';
                    setTimeout(() => { span.textContent = original; }, 1500);
                }
            }).catch(err => {
                alert('Kode room: ' + kode);
            });
        }
    </script>
</x-layout>
