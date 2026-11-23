<x-layout 
    title="Rekap Nilai Kuis - Study English Prima" 
    pageTitle="Rekap Nilai Bank Soal & Kuis" 
    pageSubtitle="Rekapitulasi lengkap riwayat pengerjaan kuis evaluasi siswa, perolehan skor, dan status ketuntasan KKM." 
    active="laporan"
>
    <!-- Tab Navigasi Sub-Modul Laporan & Analitik -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 bg-white p-3 rounded-2xl border border-slate-100 shadow-xs">
        <div class="flex items-center gap-1.5 flex-wrap">
            <a href="{{ route('guru.laporan.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span>Leger Nilai Kolektif</span>
            </a>
            <a href="{{ route('guru.laporan.materi') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span>Nilai per Materi & Kategori</span>
            </a>
            <a href="{{ route('guru.laporan.quiz') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-[#4EA87D] text-white shadow-xs">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span>Rekap Nilai Kuis</span>
            </a>
        </div>

        <a href="{{ route('guru.laporan.quiz.cetak', request()->query()) }}" 
           target="_blank"
           class="px-4 py-2 rounded-xl bg-[#4EA87D] hover:bg-[#43966E] text-white text-xs font-bold shadow-[0_4px_14px_rgba(78,168,125,0.25)] transition-all flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            <span>Cetak Rekap Kuis</span>
        </a>
    </div>

    <!-- Filter & Pencarian Rekap Kuis -->
    <div class="bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs mb-6">
        <form method="GET" action="{{ route('guru.laporan.quiz') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Pencarian Siswa -->
            <div class="relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Cari nama / NIS..." 
                       class="w-full pl-9 pr-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
            </div>

            <!-- Filter Paket Kuis -->
            <select name="quiz_id" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Paket Kuis</option>
                @foreach($daftarQuiz as $quiz)
                    <option value="{{ $quiz->id }}" {{ request('quiz_id') == $quiz->id ? 'selected' : '' }}>
                        {{ Str::limit($quiz->judul, 25) }}
                    </option>
                @endforeach
            </select>

            <!-- Filter Kelas & Rombel (Dinamis dari Data Siswa) -->
            <select name="rombel" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Kelas & Rombel</option>
                @foreach($daftarRombelGrouped as $tingkat => $rombels)
                    <optgroup label="Tingkat Kelas {{ $tingkat }}">
                        <option value="tingkat:{{ $tingkat }}" {{ request('rombel') === 'tingkat:'.$tingkat || (request('kelas') == $tingkat && !request('rombel') && !request('jurusan')) ? 'selected' : '' }}>
                            📁 Semua Kelas {{ $tingkat }}
                        </option>
                        @foreach($rombels as $itemRombel)
                            <option value="{{ $itemRombel }}" {{ request('rombel') === $itemRombel ? 'selected' : '' }}>
                                {{ $itemRombel }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>

            <!-- Filter Status Kelulusan KKM -->
            <div class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="flex-1 px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                    <option value="">Semua Status</option>
                    <option value="tuntas" {{ request('status') == 'tuntas' ? 'selected' : '' }}>Tuntas (≥{{ $kkm }})</option>
                    <option value="remedial" {{ request('status') == 'remedial' ? 'selected' : '' }}>Remedial (<{{ $kkm }})</option>
                </select>

                @if(request('q') || request('quiz_id') || request('kelas') || request('jurusan') || request('rombel') || request('status'))
                    <a href="{{ route('guru.laporan.quiz') }}" 
                       class="p-2.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition-colors"
                       title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Ringkasan Statistik Evaluasi Kuis -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <path d="M9 14l2 2 4-4"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Peserta Kuis</p>
                <h3 class="text-xl font-black text-slate-800">{{ $totalRiwayat }} Pengerjaan</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rerata Skor Kuis</p>
                <h3 class="text-xl font-black text-slate-800">{{ $rataRataSkor }} <span class="text-xs text-slate-400 font-medium">/ 100</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tuntas KKM (≥{{ $kkm }})</p>
                <h3 class="text-xl font-black text-[#2E7352]">{{ $totalTuntas }} Siswa</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Perlu Remedial</p>
                <h3 class="text-xl font-black text-rose-700">{{ $totalRemedial }} Siswa</h3>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi Nilai Kuis -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-slate-800 text-sm">Daftar Hasil Nilai Kuis Siswa</h3>
                <p class="text-xs text-slate-400 mt-0.5">Rincian skor, akurasi jawaban benar, dan durasi pengerjaan siswa</p>
            </div>
            <span class="text-xs font-bold px-3 py-1 bg-slate-100 rounded-full text-slate-600">
                Menampilkan: {{ $riwayatQuiz->count() }} dari {{ $riwayatQuiz->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">No</th>
                        <th class="py-3.5 px-6">Nama Siswa & NIS</th>
                        <th class="py-3.5 px-6">Rombel Kelas</th>
                        <th class="py-3.5 px-6">Paket Kuis</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6 text-center">Jawaban Benar</th>
                        <th class="py-3.5 px-6 text-center">Skor Kuis</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6">Waktu Pengerjaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($riwayatQuiz as $index => $item)
                        @php
                            $isTuntas = ($item->skor ?? 0) >= $kkm;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-slate-400 font-semibold">
                                {{ $riwayatQuiz->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ $item->siswa ? route('guru.siswa.show', $item->siswa->id) : '#' }}" 
                                   class="font-bold text-slate-800 text-sm hover:text-[#4EA87D] transition-colors">
                                    {{ $item->siswa->nama_lengkap ?? 'Siswa tidak ditemukan' }}
                                </a>
                                <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                                    NIS: {{ $item->siswa->nis ?? '-' }}
                                </p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-0.5 rounded-md bg-[#EAF5EF] text-[#2E7352] font-semibold text-xs border border-[#D7EDE1]/70">
                                    {{ $item->siswa->kelas_lengkap ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                {{ $item->quiz->judul ?? 'Kuis telah dihapus' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                    {{ $item->quiz->kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center font-mono font-bold text-slate-700">
                                {{ $item->jumlah_benar ?? 0 }} / {{ $item->total_soal ?? 0 }}
                            </td>
                            <td class="py-4 px-6 text-center font-mono font-extrabold text-sm {{ $isTuntas ? 'text-[#4EA87D]' : 'text-rose-600' }}">
                                {{ $item->skor !== null ? $item->skor : 0 }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $isTuntas ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-rose-50 text-rose-700 border border-rose-200/60' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isTuntas ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $isTuntas ? 'Tuntas' : 'Remedial' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-[11px] text-slate-500">
                                {{ $item->completed_at ? $item->completed_at->translatedFormat('d M Y, H:i') : ($item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') : '-') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2.5">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                        <path d="M9 14l2 2 4-4"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-600">Belum ada riwayat hasil pengerjaan kuis</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Hasil pengerjaan kuis siswa yang memenuhi kriteria filter akan tampil di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayatQuiz->total() > 0)
            <div class="px-6 py-3.5 border-t border-slate-100">
                <x-pagination :paginator="$riwayatQuiz" />
            </div>
        @endif
    </div>
</x-layout>
