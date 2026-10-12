<x-layout 
    title="Transkrip Nilai Siswa - {{ $siswa->nama_lengkap }}" 
    pageTitle="Transkrip & Rapor Belajar Siswa" 
    pageSubtitle="Detail capaian belajar individu, riwayat nilai per modul materi, listening, dan paket kuis." 
    active="siswa"
>
    <!-- Bar Notifikasi Sukses -->
    @if(session('success'))
        <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold shrink-0">✓</span>
                <p>{{ session('success') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-base px-2">×</button>
        </div>
    @endif

    <!-- 1. Header Profil Siswa & Aksi Cepat -->
    <div class="bg-white rounded-3xl p-6 lg:p-8 border border-slate-100 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4 lg:gap-5">
            <!-- Avatar Inisial Besar -->
            <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl bg-gradient-to-br from-[#1E6BFF] to-[#0A56E2] text-white flex items-center justify-center font-extrabold text-2xl lg:text-3xl shadow-[0_8px_20px_rgba(30,107,255,0.25)] shrink-0">
                {{ strtoupper(substr($siswa->nama_lengkap ?? 'S', 0, 1)) }}
            </div>

            <div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h2 class="text-xl lg:text-2xl font-bold text-slate-800 tracking-tight">
                        {{ $siswa->nama_lengkap }}
                    </h2>
                    <span class="px-3 py-1 rounded-full bg-blue-50 text-[#1E6BFF] font-semibold text-xs">
                        {{ $siswa->kelas_lengkap ?: 'Rombel belum diatur' }}
                    </span>
                    @if($siswa->no_absen)
                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-semibold text-xs">
                            Absen #{{ $siswa->no_absen }}
                        </span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-slate-400 font-medium">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        {{ $siswa->email }}
                    </span>
                    @if($siswa->nis)
                        <span>NIS: <strong class="text-slate-600 font-mono">{{ $siswa->nis }}</strong></span>
                    @endif
                    @if($siswa->nisn)
                        <span>NISN: <strong class="text-slate-600 font-mono">{{ $siswa->nisn }}</strong></span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Cepat Guru -->
        <div class="flex items-center gap-2.5 flex-wrap self-stretch md:self-auto justify-end">
            <!-- Reset Password -->
            <form action="{{ route('guru.siswa.reset_password', $siswa) }}" 
                  method="POST" 
                  onsubmit="return confirm('Reset kata sandi siswa &quot;{{ addslashes($siswa->nama_lengkap) }}&quot; ke default &quot;12345678&quot;?')" 
                  class="inline">
                @csrf
                <button type="submit" 
                        class="px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-xs transition-colors flex items-center gap-1.5 shadow-xs">
                    <svg class="w-3.5 h-3.5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Reset Password
                </button>
            </form>

            <!-- Edit Siswa -->
            <a href="{{ route('guru.siswa.edit', $siswa) }}" 
               class="px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Akun
            </a>

            <!-- Kembali -->
            <a href="{{ route('guru.siswa.index') }}" 
               class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- 2. Ringkasan Statistik Nilai Siswa -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
        <x-stat-card 
            title="Rata-rata Latihan Materi" 
            :value="$rataMateri . ' / 100'" 
            icon="📚" 
            color="blue" 
        />
        <x-stat-card 
            title="Rata-rata Listening Audio" 
            :value="$rataListening . ' / 100'" 
            icon="🎧" 
            color="purple" 
        />
        <x-stat-card 
            title="Rata-rata Ujian Kuis" 
            :value="$rataQuiz . ' / 100'" 
            icon="📝" 
            color="emerald" 
        />
        <x-stat-card 
            title="Total Perolehan XP" 
            :value="number_format($totalXp) . ' XP'" 
            icon="⚡" 
            color="amber" 
        />
    </div>

    <!-- 3. Navigasi Tab Transkrip Nilai Digital -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <!-- Header Tab Switcher -->
        <div class="border-b border-slate-100 px-6 pt-4 bg-slate-50/50 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="switchTab('materi')" 
                        id="tab-btn-materi"
                        class="tab-btn px-4 py-3 rounded-t-2xl font-bold text-xs border-b-2 border-[#1E6BFF] text-[#1E6BFF] bg-white transition-all flex items-center gap-2">
                    <span>📚</span> Nilai Modul Materi
                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-[#1E6BFF] text-[10px] font-bold">
                        {{ $siswa->materiProgres->count() }}/{{ $semuaMateri->count() }}
                    </span>
                </button>

                <button type="button" 
                        onclick="switchTab('listening')" 
                        id="tab-btn-listening"
                        class="tab-btn px-4 py-3 rounded-t-2xl font-bold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center gap-2">
                    <span>🎧</span> Nilai Modul Listening
                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                        {{ $siswa->listeningProgres->count() }}/{{ $semuaListening->count() }}
                    </span>
                </button>

                <button type="button" 
                        onclick="switchTab('quiz')" 
                        id="tab-btn-quiz"
                        class="tab-btn px-4 py-3 rounded-t-2xl font-bold text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all flex items-center gap-2">
                    <span>📝</span> Nilai Paket Kuis & Ujian
                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                        {{ $siswa->quizProgres->count() }}/{{ $semuaQuiz->count() }}
                    </span>
                </button>
            </div>

            <span class="text-xs text-slate-400 font-medium pb-2">
                Diperbarui otomatis saat siswa selesai mengerjakan di HP
            </span>
        </div>

        <!-- ==================== TAB 1: NILAI MODUL MATERI ==================== -->
        <div id="tab-content-materi" class="tab-content">
            <div class="p-5 lg:p-6 bg-blue-50/30 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Transkrip Nilai Pemahaman Materi</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Nilai dievaluasi dari latihan soal interaktif di Step 4 pada masing-masing modul materi.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">No</th>
                            <th class="py-3.5 px-6">Judul Modul Materi</th>
                            <th class="py-3.5 px-6">Kategori</th>
                            <th class="py-3.5 px-6 text-center">Status</th>
                            <th class="py-3.5 px-6 text-center">Nilai Skor</th>
                            <th class="py-3.5 px-6 text-center">Reward XP</th>
                            <th class="py-3.5 px-6">Tanggal Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($semuaMateri as $idx => $materi)
                            @php
                                $progres = $materiProgresMap->get($materi->id);
                                $isSelesai = $progres && $progres->status === 'selesai';
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-slate-400 font-semibold">{{ $idx + 1 }}</td>
                                <td class="py-4 px-6">
                                    <p class="font-bold text-slate-800 text-sm">{{ $materi->judul }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Kelas {{ $materi->tingkat_kelas }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        {{ $materi->kategori ?: 'General' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($isSelesai)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-[11px] font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Belum Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center font-bold">
                                    @if($isSelesai)
                                        <span class="text-sm px-3 py-1 rounded-xl {{ ($progres->skor ?? 0) >= 75 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ $progres->skor ?? 0 }} / 100
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-amber-600">
                                    {{ $isSelesai ? '+' . ($progres->xp_didapat ?? 0) . ' XP' : '-' }}
                                </td>
                                <td class="py-4 px-6 text-slate-500 font-mono text-[11px]">
                                    {{ $isSelesai && $progres->completed_at ? $progres->completed_at->format('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <x-empty-state 
                                colspan="7" 
                                icon="📚" 
                                title="Belum ada modul materi" 
                                subtitle="Materi pembelajaran belum ditambahkan oleh guru." 
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== TAB 2: NILAI MODUL LISTENING ==================== -->
        <div id="tab-content-listening" class="tab-content hidden">
            <div class="p-5 lg:p-6 bg-purple-50/30 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Transkrip Nilai Modul Listening Audio</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Nilai dievaluasi dari butir soal latihan audio percakapan/monolog.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">No</th>
                            <th class="py-3.5 px-6">Judul Modul Listening</th>
                            <th class="py-3.5 px-6">Tingkat Kesulitan</th>
                            <th class="py-3.5 px-6 text-center">Status</th>
                            <th class="py-3.5 px-6 text-center">Nilai Skor</th>
                            <th class="py-3.5 px-6 text-center">Reward XP</th>
                            <th class="py-3.5 px-6">Tanggal Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($semuaListening as $idx => $listening)
                            @php
                                $progres = $listeningProgresMap->get($listening->id);
                                $isSelesai = $progres && $progres->status === 'selesai';
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-slate-400 font-semibold">{{ $idx + 1 }}</td>
                                <td class="py-4 px-6">
                                    <p class="font-bold text-slate-800 text-sm">{{ $listening->judul }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $listening->audio_count ?? 1 }} Audio Track</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 text-[11px] font-semibold capitalize">
                                        {{ $listening->tingkat_kesulitan ?: 'Menengah' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($isSelesai)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-[11px] font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Belum Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center font-bold">
                                    @if($isSelesai)
                                        <span class="text-sm px-3 py-1 rounded-xl {{ ($progres->skor ?? 0) >= 75 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ $progres->skor ?? 0 }} / 100
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-amber-600">
                                    {{ $isSelesai ? '+' . ($progres->xp_didapat ?? 0) . ' XP' : '-' }}
                                </td>
                                <td class="py-4 px-6 text-slate-500 font-mono text-[11px]">
                                    {{ $isSelesai && $progres->completed_at ? $progres->completed_at->format('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <x-empty-state 
                                colspan="7" 
                                icon="🎧" 
                                title="Belum ada modul listening" 
                                subtitle="Modul listening belum ditambahkan oleh guru." 
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== TAB 3: NILAI PAKET KUIS & UJIAN ==================== -->
        <div id="tab-content-quiz" class="tab-content hidden">
            <div class="p-5 lg:p-6 bg-emerald-50/30 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Transkrip Nilai Paket Kuis & Room Ujian</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Nilai hasil pengerjaan kuis resmi dan ujian interaktif via Room Code.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">No</th>
                            <th class="py-3.5 px-6">Nama Paket Kuis & Room</th>
                            <th class="py-3.5 px-6">Kategori & Kelas</th>
                            <th class="py-3.5 px-6 text-center">Butir Benar</th>
                            <th class="py-3.5 px-6 text-center">Nilai Akhir</th>
                            <th class="py-3.5 px-6 text-center">Waktu Pengerjaan</th>
                            <th class="py-3.5 px-6">Tanggal Ujian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($semuaQuiz as $idx => $quiz)
                            @php
                                $progres = $quizProgresMap->get($quiz->id);
                                $isSelesai = $progres && $progres->status === 'selesai';
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-slate-400 font-semibold">{{ $idx + 1 }}</td>
                                <td class="py-4 px-6">
                                    <p class="font-bold text-slate-800 text-sm">{{ $quiz->judul }}</p>
                                    <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold text-[#1E6BFF] bg-blue-50 px-2 py-0.5 rounded-md mt-1">
                                        ROOM: {{ $quiz->kode_room }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-semibold text-slate-700">{{ $quiz->kategori }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Kelas {{ $quiz->tingkat_kelas }}</p>
                                </td>
                                <td class="py-4 px-6 text-center font-bold">
                                    @if($isSelesai)
                                        <span class="text-slate-800">
                                            {{ $progres->jumlah_benar ?? 0 }} / {{ $progres->total_soal ?? $quiz->soals_count ?? 0 }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">Belum Ujian</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center font-bold">
                                    @if($isSelesai)
                                        <span class="text-sm px-3 py-1 rounded-xl {{ ($progres->skor ?? 0) >= 75 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ $progres->skor ?? 0 }} / 100
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center font-mono text-slate-600 text-xs">
                                    @if($isSelesai && $progres->waktu_pengerjaan_detik)
                                        {{ gmdate('i:s', $progres->waktu_pengerjaan_detik) }} Menit
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-slate-500 font-mono text-[11px]">
                                    {{ $isSelesai && $progres->completed_at ? $progres->completed_at->format('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <x-empty-state 
                                colspan="7" 
                                icon="📝" 
                                title="Belum ada paket kuis" 
                                subtitle="Paket kuis ujian belum ditambahkan oleh guru." 
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script Tab Switching Vanilla JS -->
    <script>
        function switchTab(tabName) {
            // Sembunyikan semua tab content
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

            // Reset styling tab button
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-[#1E6BFF]', 'text-[#1E6BFF]', 'bg-white');
                btn.classList.add('border-transparent', 'text-slate-500');
            });

            // Tampilkan tab yang dipilih
            const targetContent = document.getElementById('tab-content-' + tabName);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }

            // Aktifkan button yang dipilih
            const targetBtn = document.getElementById('tab-btn-' + tabName);
            if (targetBtn) {
                targetBtn.classList.remove('border-transparent', 'text-slate-500');
                targetBtn.classList.add('border-[#1E6BFF]', 'text-[#1E6BFF]', 'bg-white');
            }
        }
    </script>
</x-layout>
