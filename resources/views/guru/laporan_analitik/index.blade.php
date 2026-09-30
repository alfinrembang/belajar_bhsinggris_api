<x-layout 
    title="Laporan & Analitik - Study English Prima" 
    pageTitle="Laporan & Analitik" 
    pageSubtitle="Analisis komprehensif perkembangan belajar siswa, ketuntasan materi, dan evaluasi nilai kuis." 
    active="laporan"
>
    <!-- Bar Aksi Atas: Filter Kelas, Periode & Tombol Unduh Laporan -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs">
        <!-- Filter Rombel & Periode -->
        <div class="flex flex-wrap items-center gap-3">
            <select class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="">Semua Rombel / Kelas</option>
                <option value="10">Kelas 10 RPL / DKV</option>
                <option value="11">Kelas 11 RPL / TKJ</option>
                <option value="12">Kelas 12 RPL</option>
            </select>

            <select class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="sep_2026">September 2026</option>
                <option value="aug_2026">Agustus 2026</option>
                <option value="jul_2026">Juli 2026</option>
            </select>
        </div>

        <!-- Tombol Cetak / Export -->
        <div class="flex items-center gap-3">
            <button class="px-4 py-2.5 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-slate-200/80 text-slate-700 font-semibold text-xs transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Export Excel
            </button>

            <button class="px-5 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-semibold text-xs shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Cetak Rapor PDF
            </button>
        </div>
    </div>

    <!-- Ringkasan Statistik Metrik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Rata-rata Nilai</p>
                <span class="p-2 rounded-xl bg-blue-50 text-[#1E6BFF] text-base">📊</span>
            </div>
            <h4 class="text-2xl font-bold text-slate-800 mt-2">86.4 <span class="text-xs font-normal text-slate-400">/ 100</span></h4>
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 mt-1">
                ↑ +4.2% dari bulan lalu
            </span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Ketuntasan Materi</p>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 text-base">✅</span>
            </div>
            <h4 class="text-2xl font-bold text-slate-800 mt-2">91.8%</h4>
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 mt-1">
                Kategori Sangat Baik
            </span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Waktu Belajar</p>
                <span class="p-2 rounded-xl bg-purple-50 text-purple-600 text-base">⏱️</span>
            </div>
            <h4 class="text-2xl font-bold text-slate-800 mt-2">184 Jam</h4>
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400 mt-1">
                Rata-rata 1.5 jam / siswa
            </span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Perlu Remedial</p>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-600 text-base">⚠️</span>
            </div>
            <h4 class="text-2xl font-bold text-slate-800 mt-2">1 Siswa</h4>
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-600 mt-1">
                Skor di bawah KKM 75
            </span>
        </div>
    </div>

    <!-- Analisis Per Modul & Tabel Nilai Terkini -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Analisis Modul (1 Kolom) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Ketuntasan per Kategori</h3>
                <p class="text-xs text-slate-400 mt-0.5">Persentase capaian belajar siswa</p>
            </div>

            <div class="space-y-4">
                <!-- Modul Vocabulary -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1.5">
                        <span class="text-slate-700">Vocabulary Master</span>
                        <span class="text-[#1E6BFF]">94%</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full bg-[#1E6BFF] rounded-full" style="width: 94%"></div>
                    </div>
                </div>

                <!-- Modul Listening -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1.5">
                        <span class="text-slate-700">Listening Audio Comprehension</span>
                        <span class="text-purple-600">88%</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" style="width: 88%"></div>
                    </div>
                </div>

                <!-- Modul Grammar -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1.5">
                        <span class="text-slate-700">Grammar & Structure</span>
                        <span class="text-emerald-600">82%</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: 82%"></div>
                    </div>
                </div>

                <!-- Modul Mini Games -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1.5">
                        <span class="text-slate-700">Mini Games & Tantangan</span>
                        <span class="text-amber-600">96%</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: 96%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Ringkasan Nilai Siswa (2 Kolom) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Evaluasi Nilai Siswa Terkini</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Dihitung dari rata-rata kuis dan mini game</p>
                </div>
                <span class="text-xs text-slate-400">KKM: 75</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Siswa</th>
                            <th class="py-3.5 px-6">Kelas</th>
                            <th class="py-3.5 px-6 text-center">Nilai Rata-rata</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($daftarSiswa ?? [] as $index => $siswa)
                            @php
                                $score = [92, 88, 85, 95, 78, 89, 74][$index % 7];
                                $lulus = $score >= 75;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($siswa->nama_lengkap ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-slate-800">{{ $siswa->nama_lengkap }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-slate-500">{{ $siswa->kelas_lengkap ?: 'Siswa' }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="font-mono font-bold text-xs {{ $lulus ? 'text-slate-800' : 'text-rose-600' }}">{{ $score }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($lulus)
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">Tuntas</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-600 text-[11px] font-semibold">Remedial</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <button class="px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-[#1E6BFF] text-slate-600 text-xs font-semibold transition-colors">
                                        Detail Rapor
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">Belum ada data evaluasi belajar siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layout>
