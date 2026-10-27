<x-layout 
    title="Laporan & Analitik - Study English Prima" 
    pageTitle="Laporan & Analitik Belajar" 
    pageSubtitle="Analisis komprehensif perkembangan belajar siswa, ketuntasan KKM, dan evaluasi nilai modul." 
    active="laporan"
>
    <!-- Bar Aksi Atas: Filter Kelas, Jurusan, Status & Tombol Unduh / Cetak -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs">
        <form method="GET" action="{{ route('guru.laporan.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto flex-1">
            <!-- Pencarian Siswa -->
            <div class="relative flex-1 max-w-xs">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Cari siswa / NIS..." 
                       class="w-full pl-9 pr-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all">
            </div>

            <!-- Filter Tingkat Kelas -->
            <select name="kelas" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Tingkat</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>

            <!-- Filter Jurusan -->
            <select name="jurusan" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Jurusan</option>
                @foreach($daftarJurusan as $jur)
                    <option value="{{ $jur }}" {{ request('jurusan') == $jur ? 'selected' : '' }}>{{ $jur }}</option>
                @endforeach
            </select>

            <!-- Filter Status KKM -->
            <select name="status" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Status</option>
                <option value="tuntas" {{ request('status') == 'tuntas' ? 'selected' : '' }}>Tuntas (≥75)</option>
                <option value="remedial" {{ request('status') == 'remedial' ? 'selected' : '' }}>Remedial (<75)</option>
            </select>

            @if(request('q') || request('kelas') || request('jurusan') || request('status'))
                <a href="{{ route('guru.laporan.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-rose-600 transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Reset
                </a>
            @endif
        </form>

        <!-- Tombol Cetak & Export File -->
        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('guru.laporan.export_excel', request()->query()) }}" 
               class="px-4 py-2.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/90 text-emerald-800 font-bold text-xs transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="8" y1="13" x2="16" y2="13"></line>
                    <line x1="8" y1="17" x2="16" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Export Excel (.xlsx)
            </a>

            <a href="{{ route('guru.laporan.cetak', request()->query()) }}" 
               target="_blank"
               class="px-5 py-2.5 rounded-2xl bg-[#4EA87D] hover:bg-[#43966E] text-white font-bold text-xs shadow-[0_6px_16px_rgba(78,168,125,0.28)] transition-all flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Cetak Rapor Leger
            </a>
        </div>
    </div>

    <!-- 1. Ringkasan Metrik KPI Utama (Komponen Reusable x-stat-card) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
        <x-stat-card 
            title="Rata-rata Nilai Siswa" 
            :value="$rataRataKeseluruhan . ' / 100'" 
            icon="chart" 
            color="blue" 
        />
        <x-stat-card 
            title="Tingkat Ketuntasan KKM" 
            :value="$persentaseKetuntasan . '%'" 
            icon="check" 
            color="emerald" 
        />
        <x-stat-card 
            title="Total Pengerjaan Soal" 
            :value="$totalAktivitasBelajar . ' Submit'" 
            icon="zap" 
            color="purple" 
        />
        <x-stat-card 
            title="Siswa Perlu Remedial" 
            :value="$totalRemedial . ' Siswa'" 
            icon="alert" 
            color="rose" 
        />
    </div>

    <!-- 2. Analisis Performa per Modul & Komparasi Rombel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Card Ketuntasan per Modul Pembelajaran (Komponen Reusable x-progress-item) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] space-y-5 flex flex-col justify-between">
            <div>
                <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-[#EAF5EF] text-[#4EA87D] flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                        </svg>
                    </span>
                    Ketuntasan per Modul Belajar
                </h3>
                <p class="text-xs text-slate-400 mt-0.5 truncate" title="Progres ketuntasan & rerata modul">Progres ketuntasan & rerata modul</p>

                <div class="space-y-4 pt-4">
                    <x-progress-item 
                        label="Reading & Modul Materi"
                        :percentage="$ketuntasanMateriPersen"
                        :averageScore="$rataModulMateri"
                        color="blue"
                        icon="book"
                    />

                    <x-progress-item 
                        label="Listening Audio Comprehension"
                        :percentage="$ketuntasanListeningPersen"
                        :averageScore="$rataModulListening"
                        color="purple"
                        icon="headphones"
                    />

                    <x-progress-item 
                        label="Bank Soal & Kuis Ujian"
                        :percentage="$ketuntasanQuizPersen"
                        :averageScore="$rataModulQuiz"
                        color="emerald"
                        icon="file-text"
                    />
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-500 leading-relaxed mt-4 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-[#4EA87D] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <div>
                    <span class="font-bold text-slate-700">Rumus Nilai Akhir:</span> 30% Modul Materi + 30% Modul Listening + 40% Kuis/Ujian. Standar KKM kelulusan adalah <strong>{{ $kkm }}</strong>.
                </div>
            </div>
        </div>

        <!-- Card Komparasi Rata-rata Nilai per Rombel -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </span>
                        Performa Nilai per Rombel
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">
                        {{ $komparasiRombel->count() }} Kelas
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-0.5 truncate" title="Rerata capaian nilai per rombel">Rerata capaian nilai per rombel</p>

                <!-- Scrollable list: tetap rapi dan tidak melar ke bawah meski ada puluhan kelas -->
                <div class="space-y-2.5 pt-3 max-h-[300px] overflow-y-auto pr-1">
                    @forelse($komparasiRombel as $rombel)
                        <a href="{{ route('guru.laporan.index', array_merge(request()->except(['page', 'kelas', 'jurusan']), ['kelas' => $rombel['kelas'], 'jurusan' => $rombel['jurusan']])) }}#tabel-leger"
                           title="Klik untuk filter tabel ke kelas {{ $rombel['nama_kelas'] }}"
                           class="group block p-3 rounded-2xl bg-slate-50 hover:bg-[#EAF5EF]/50 border border-slate-100 hover:border-[#D7EDE1] transition-all cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="font-bold text-slate-800 group-hover:text-[#4EA87D] text-xs transition-colors">{{ $rombel['nama_kelas'] }}</h4>
                                        <span class="text-[10px] text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity">↗</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $rombel['total_siswa'] }} Siswa ({{ $rombel['total_tuntas'] }} Tuntas)</p>
                                </div>
                                <div class="text-right">
                                    <span class="font-mono font-extrabold text-xs text-[#4EA87D]">{{ $rombel['rata_nilai'] }}</span>
                                    <span class="block text-[10px] text-slate-400">Rerata</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400 py-6 text-center">Belum ada distribusi rombel kelas.</p>
                    @endforelse
                </div>
            </div>

            <div class="text-[10px] text-slate-400 pt-2 border-t border-slate-50 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <span>Klik kelas untuk memfilter tabel leger</span>
            </div>
        </div>

        <!-- Card Siswa Berprestasi & Perlu Perhatian -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex flex-col justify-between space-y-4">
            <div class="space-y-4">
                <div>
                    <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                <path d="M4 22h16"></path>
                                <path d="M10 14.66V17c0 .55-.45 1-1 1H7"></path>
                                <path d="M14 14.66V17c0 .55.45 1 1 1h2"></path>
                                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                            </svg>
                        </span>
                        Siswa Berprestasi & Perhatian
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5 truncate" title="Siswa nilai unggul & remedial">Siswa nilai unggul & remedial</p>
                </div>

                <!-- Top 3 Siswa Terunggul -->
                <div>
                    <span class="text-[11px] font-extrabold text-emerald-600 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                        <svg class="w-3.5 h-3.5 text-emerald-600 fill-emerald-600" viewBox="0 0 24 24" stroke="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        Top 3 Siswa Terunggul
                    </span>
                    <div class="space-y-2">
                        @forelse($topSiswa as $ts)
                            <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50/50 border border-emerald-100 text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-[11px]">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $ts['nama_lengkap'] }}</p>
                                        <span class="text-[10px] text-slate-400">{{ $ts['kelas_lengkap'] }}</span>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-emerald-700">{{ $ts['nilai_akhir'] }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-2">Belum ada data nilai.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Siswa Butuh Remedial (< KKM) -->
                <div class="pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-extrabold text-rose-600 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                            Perlu Remedial (&lt;{{ $kkm }})
                        </span>
                        @if($totalRemedial > 0)
                            <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">
                                Total: {{ $totalRemedial }} Siswa
                            </span>
                        @endif
                    </div>
                    
                    <div class="space-y-2 max-h-[140px] overflow-y-auto pr-1">
                        @forelse($remedialSiswa as $rs)
                            <div class="flex items-center justify-between p-2 rounded-xl bg-rose-50/50 border border-rose-100 text-xs">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $rs['nama_lengkap'] }}</p>
                                    <span class="text-[10px] text-slate-400">{{ $rs['kelas_lengkap'] }}</span>
                                </div>
                                <span class="font-mono font-bold text-rose-600">{{ $rs['nilai_akhir'] }}</span>
                            </div>
                        @empty
                            <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-semibold text-center flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                Luar biasa! Semua siswa telah mencapai batas KKM.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            @if($totalRemedial > 0)
                <div class="pt-2 border-t border-slate-100">
                    <a href="{{ route('guru.laporan.index', array_merge(request()->except(['page', 'status']), ['status' => 'remedial'])) }}#tabel-leger"
                       class="w-full py-2 px-3 rounded-xl bg-rose-50 hover:bg-rose-100/80 border border-rose-100 text-rose-700 text-[11px] font-bold flex items-center justify-center gap-1.5 transition-colors cursor-pointer">
                        <span>Lihat Semua {{ $totalRemedial }} Siswa Remedial</span>
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            @endif
        </div>

    </div>

    <!-- 3. Tabel Rekapitulasi Nilai Kolektif Seluruh Siswa (Leger Nilai) -->
    <div id="tabel-leger" class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden scroll-mt-6">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-extrabold text-slate-800 text-sm">Leger Rekapitulasi Nilai Kolektif</h3>
                <p class="text-xs text-slate-400 mt-0.5">Rincian nilai per pilar materi, nilai akhir, serta status ketuntasan KKM</p>
            </div>
            <span class="text-xs font-bold px-3 py-1 bg-slate-100 rounded-full text-slate-600 self-start sm:self-auto">
                Total Siswa: {{ $paginatedSiswa->total() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">No</th>
                        <th class="py-3.5 px-6">Nama Siswa & NIS</th>
                        <th class="py-3.5 px-6">Rombel Kelas</th>
                        <th class="py-3.5 px-6 text-center">Materi (30%)</th>
                        <th class="py-3.5 px-6 text-center">Listening (30%)</th>
                        <th class="py-3.5 px-6 text-center">Kuis (40%)</th>
                        <th class="py-3.5 px-6 text-center">Nilai Akhir</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($paginatedSiswa as $index => $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-slate-400 font-semibold">
                                {{ $paginatedSiswa->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-[#EAF5EF] text-[#4EA87D] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($item['nama_lengkap'] ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('guru.siswa.show', $item['id']) }}" class="font-bold text-slate-800 text-sm hover:text-[#4EA87D] transition-colors">
                                            {{ $item['nama_lengkap'] }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">NIS: {{ $item['nis'] ?: '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <span class="whitespace-nowrap px-2.5 py-0.5 rounded-md bg-[#EAF5EF] text-[#2E7352] font-semibold text-xs border border-[#D7EDE1]/70 inline-flex items-center">
                                    {{ $item['kelas_lengkap'] ?: 'Belum diatur' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center font-mono font-bold text-slate-700">
                                {{ $item['rata_materi'] }}
                            </td>
                            <td class="py-4 px-6 text-center font-mono font-bold text-slate-700">
                                {{ $item['rata_listening'] }}
                            </td>
                            <td class="py-4 px-6 text-center font-mono font-bold text-slate-700">
                                {{ $item['rata_quiz'] }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="font-mono font-extrabold text-sm {{ $item['is_tuntas'] ? 'text-slate-800' : 'text-rose-600' }}">
                                    {{ $item['nilai_akhir'] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <x-badge-ketuntasan :isTuntas="$item['is_tuntas']" :kkm="$kkm" />
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('guru.siswa.show', $item['id']) }}" 
                                   class="px-3.5 py-1.5 rounded-xl bg-slate-50 hover:bg-[#EAF5EF] text-slate-600 hover:text-[#4EA87D] text-xs font-bold transition-colors inline-flex items-center gap-1.5">
                                    <span>Detail Rapor</span>
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <x-empty-state 
                            colspan="9" 
                            icon="📊" 
                            title="Belum ada data evaluasi nilai siswa" 
                            subtitle="Silakan sesuaikan filter pencarian atau pastikan siswa telah mulai mengerjakan materi dan kuis."
                        />
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paginatedSiswa->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $paginatedSiswa->links() }}
            </div>
        @endif
    </div>
</x-layout>
