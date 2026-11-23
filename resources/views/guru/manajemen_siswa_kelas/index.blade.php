<x-layout 
    title="Manajemen Siswa & Kelas - Study English Prima" 
    pageTitle="Manajemen Siswa & Kelas" 
    pageSubtitle="Kelola data akun siswa, distribusi rombel kelas, pantau keaktifan belajar, dan reset kata sandi." 
    active="siswa"
>
    <!-- Bar Notifikasi Sukses -->
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </span>
                <p>{{ session('success') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-base px-2">×</button>
        </div>
    @endif

    <!-- Bar Notifikasi Error / Peringatan -->
    @if(session('error'))
        <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200/80 text-rose-800 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-sm font-bold shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </span>
                <p>{{ session('error') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold text-base px-2">×</button>
        </div>
    @endif

    <!-- Bar Aksi Atas: Pencarian, Filter Kelas/Jurusan & Tombol Tambah Siswa -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)]">
        <form method="GET" action="{{ route('guru.siswa.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full md:w-auto flex-1">
            <!-- Pencarian Siswa -->
            <div class="relative flex-1 max-w-md">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari nama siswa, NIS, NISN, email..." 
                       class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
            </div>

            <!-- Filter Rombel Kelas Terpadu (Dinamis Sesuai Siswa Terdaftar) -->
            <select name="rombel" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Rombel & Kelas</option>
                @if(isset($daftarRombelGrouped) && !empty($daftarRombelGrouped))
                    @foreach($daftarRombelGrouped as $tingkat => $rombelList)
                        <optgroup label="Tingkat Kelas {{ $tingkat }}">
                            <option value="tingkat:{{ $tingkat }}" {{ request('rombel') === 'tingkat:'.$tingkat || request('kelas') === (string)$tingkat ? 'selected' : '' }}>
                                Semua Kelas {{ $tingkat }}
                            </option>
                            @foreach($rombelList as $rItem)
                                <option value="{{ $rItem }}" {{ request('rombel') === $rItem ? 'selected' : '' }}>
                                    {{ $rItem }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @else
                    @foreach($daftarKelas as $k)
                        <option value="tingkat:{{ $k }}" {{ request('rombel') === 'tingkat:'.$k || request('kelas') === (string)$k ? 'selected' : '' }}>
                            Kelas {{ $k }}
                        </option>
                    @endforeach
                @endif
            </select>

            @if(request('q') || request('rombel') || request('kelas') || request('jurusan'))
                <a href="{{ route('guru.siswa.index') }}" class="px-3 py-2 text-xs font-medium text-slate-500 hover:text-rose-600 transition-colors flex items-center gap-1 self-center">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Reset
                </a>
            @endif
        </form>

        <!-- Tombol Aksi Kanan: Import Excel & Tambah Siswa Baru -->
        <div class="flex items-center gap-2.5 shrink-0 self-start sm:self-auto">
            <!-- Tombol Buka Modal Import Excel -->
            <button type="button" 
                    onclick="bukaModalImportExcel()" 
                    class="px-3.5 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100/90 text-emerald-800 border border-emerald-200/90 font-semibold text-xs shadow-xs transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
                <span>Import Excel</span>
            </button>

            <!-- Tombol Tambah Siswa Baru -->
            <a href="{{ route('guru.siswa.create') }}" 
               class="px-4 py-2.5 rounded-xl bg-[#4EA87D] hover:bg-[#43966E] text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Siswa Baru</span>
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik Siswa & Kelas -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Siswa Terdaftar</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 tracking-tight">{{ $totalSiswa }} Siswa</h3>
                <p class="text-[11px] text-[#2E7352] font-semibold mt-1">Siswa Terdaftar</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Rombel / Kelas Aktif</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 tracking-tight">{{ $totalRombel }} Rombel</h3>
                <p class="text-[11px] text-[#2E7352] font-semibold mt-1">Distribusi Kelas</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa Aktif Belajar</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 tracking-tight">{{ $siswaAktif }} Siswa</h3>
                <p class="text-[11px] text-[#2E7352] font-semibold mt-1">Aktivitas Terpantau</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <polyline points="17 11 19 13 23 9"></polyline>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Siswa -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] overflow-hidden">
        <div class="px-5 lg:px-6 py-4.5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Daftar Akun Siswa & Distribusi Kelas</h3>
                <p class="text-xs text-slate-400 mt-0.5">Identitas akun, nomor absen, dan riwayat belajar siswa</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 rounded-full text-slate-600 border border-slate-200/60">
                Total: {{ $daftarSiswa->total() }} Siswa
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/70 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-3.5 w-10 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <input type="checkbox" 
                                       id="selectAllCheckbox" 
                                       onchange="togglePilihSemua(this)" 
                                       title="Centang untuk memilih seluruh siswa di halaman ini"
                                       class="w-4 h-4 rounded text-[#4EA87D] focus:ring-[#4EA87D]/20 border-slate-300 cursor-pointer transition-all">
                            </div>
                        </th>
                        <th class="py-3 px-4 w-12 text-center whitespace-nowrap">No</th>
                        <th class="py-3 px-4 whitespace-nowrap">Nama Siswa & Email</th>
                        <th class="py-3 px-4 whitespace-nowrap">NIS / NISN</th>
                        <th class="py-3 px-4 whitespace-nowrap">Rombel Kelas</th>
                        <th class="py-3 px-4 text-center whitespace-nowrap">No. Absen</th>
                        <th class="py-3 px-4 text-center whitespace-nowrap">Status Akun</th>
                        <th class="py-3 px-4 text-center whitespace-nowrap">Aksi & Rapor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($daftarSiswa as $index => $siswa)
                        <tr class="hover:bg-slate-50/70 transition-colors" id="row-siswa-{{ $siswa->id }}">
                            <td class="py-3.5 px-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" 
                                           name="siswa_ids[]" 
                                           value="{{ $siswa->id }}" 
                                           data-nama="{{ $siswa->nama_lengkap }}"
                                           onchange="onSiswaCheckboxChange()"
                                           class="siswa-item-checkbox w-4 h-4 rounded text-[#4EA87D] focus:ring-[#4EA87D]/20 border-slate-300 cursor-pointer transition-all">
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400 font-semibold text-center whitespace-nowrap">
                                {{ $daftarSiswa->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0 border border-slate-200/60">
                                        {{ strtoupper(substr($siswa->nama_lengkap ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('guru.siswa.show', $siswa) }}" class="font-semibold text-slate-900 text-xs hover:text-[#4EA87D] transition-colors">
                                            {{ $siswa->nama_lengkap }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 mt-0.5 font-normal">{{ $siswa->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600 whitespace-nowrap">
                                <div>
                                    <span class="font-semibold text-slate-800">{{ $siswa->nis ?: '-' }}</span>
                                    @if($siswa->nisn)
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-normal">NISN: {{ $siswa->nisn }}</p>
                                    @endif
                                </div>
                            </td>
                            <!-- Kolom Rombel Kelas (Dipastikan Full Lurus Tanpa Wrapping) -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="whitespace-nowrap px-2.5 py-0.5 rounded-md bg-[#EAF5EF] text-[#2E7352] font-semibold text-xs inline-flex items-center gap-1.5 border border-[#D7EDE1]/70">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#4EA87D]"></span>
                                        {{ $siswa->kelas_lengkap ?: 'Belum diatur' }}
                                    </span>
                                    <div class="text-[10px] text-slate-400 font-mono">
                                        TA: {{ $siswa->tahun_ajaran_aktif ?: '-' }}
                                        @if($siswa->status_kelulusan === 'tinggal_kelas')
                                            <span class="text-amber-600 font-semibold ml-1">(Mengulang)</span>
                                        @elseif($siswa->status_kelulusan === 'lulus')
                                            <span class="text-emerald-600 font-semibold ml-1">(Lulus)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-600 whitespace-nowrap">
                                {{ $siswa->no_absen ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($siswa->api_token)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-semibold border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif di HP
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[11px] font-medium border border-slate-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Terdaftar
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- 1. Tombol Transkrip Rapor Nilai -->
                                    <a href="{{ route('guru.siswa.show', $siswa) }}" 
                                       class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-[11px] transition-colors flex items-center gap-1"
                                       title="Buka Transkrip Nilai Siswa">
                                        <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                        </svg>
                                        <span>Rapor</span>
                                    </a>

                                    <!-- 2. Tombol Reset Password Cepat -->
                                    <button type="button" 
                                            onclick="konfirmasiResetPassword('{{ route('guru.siswa.reset_password', $siswa) }}', '{{ addslashes($siswa->nama_lengkap) }}')"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-slate-100 transition-colors cursor-pointer" 
                                            title="Reset Password ke: 12345678">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </button>

                                    <!-- 3. Tombol Edit -->
                                    <a href="{{ route('guru.siswa.edit', $siswa) }}" 
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-[#4EA87D] hover:bg-slate-100 transition-colors" 
                                       title="Edit Data Siswa">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    <!-- 4. Tombol Hapus -->
                                    <button type="button" 
                                            onclick="konfirmasiHapus('{{ route('guru.siswa.destroy', $siswa) }}', 'Akun siswa {{ addslashes($siswa->nama_lengkap) }}', 'Seluruh riwayat nilai, progres materi, dan aktivitas belajar siswa ini akan dihapus permanen.')"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition-colors cursor-pointer" 
                                            title="Hapus Akun Siswa">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 px-6 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700">Belum ada data siswa ditemukan</p>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm">Daftarkan akun siswa baru atau ubah kata kunci filter pencarian Anda.</p>
                                    <a href="{{ route('guru.siswa.create') }}" 
                                       class="mt-4 px-4 py-2 rounded-xl bg-[#4EA87D] hover:bg-[#43966E] text-white font-semibold text-xs transition-all shadow-xs inline-flex items-center gap-1.5">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span>Daftarkan Siswa Baru</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarSiswa->total() > 0)
            <div class="px-6 py-3.5 border-t border-slate-100">
                <x-pagination :paginator="$daftarSiswa" />
            </div>
        @endif
    </div>

    <!-- Floating Bar Aksi Masal Siswa (Muncul saat ada siswa yang dicentang) -->
    <div id="bulkActionBar" 
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-slate-900/95 backdrop-blur-md text-white px-5 py-3 rounded-2xl shadow-2xl border border-slate-700/80 flex items-center gap-4 transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none">
        
        <div class="flex items-center gap-3">
            <!-- Tanda Centang / Master Checkbox Toggle Cepat -->
            <button type="button" 
                    onclick="togglePilihSemuaSemua()" 
                    id="btnSelectAllFloating"
                    title="Klik untuk memilih seluruh siswa secara bersamaan"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 border border-slate-700 transition-colors cursor-pointer">
                <span id="floatingCheckIcon" class="w-4 h-4 rounded-md border border-slate-400 flex items-center justify-center text-[11px] font-bold"></span>
                <span id="floatingCheckText">Pilih Semua ({{ $daftarSiswa->count() }})</span>
            </button>

            <div class="h-5 w-px bg-slate-700"></div>

            <div class="flex items-center gap-2 text-xs">
                <span class="w-2 h-2 rounded-full bg-[#4EA87D] animate-pulse"></span>
                <span class="font-bold text-white text-sm" id="selectedCountText">0</span>
                <span class="text-slate-300">siswa dipilih</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" 
                    onclick="batalPilihSemua()" 
                    class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer">
                Batal
            </button>

            <button type="button" 
                    onclick="bukaModalBulkDelete()" 
                    class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-900/30 transition-all flex items-center gap-1.5 cursor-pointer active:scale-95">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                <span>Hapus Siswa Terpilih</span>
            </button>
        </div>
    </div>

    <!-- MODAL: Import Data Siswa dari File Excel -->
    <div id="modalImportExcel" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-200">
        <div class="fixed inset-0" onclick="tutupModalImportExcel()"></div>
        
        <div id="modalImportExcelCard" 
             class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden transform scale-95 transition-all duration-200 z-10 p-6 lg:p-7 space-y-5">
            
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="8" y1="13" x2="16" y2="13"></line>
                            <line x1="8" y1="17" x2="16" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Import Data Siswa dari Excel</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Mendaftarkan banyak akun siswa sekaligus lewat dokumen spreadsheet</p>
                    </div>
                </div>
                <button type="button" onclick="tutupModalImportExcel()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- Panduan Format & Fitur Login -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 space-y-2.5">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                    <span class="font-bold text-slate-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#4EA87D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        Format & Ketentuan Login Siswa:
                    </span>
                    <a href="{{ route('guru.siswa.template_excel') }}" 
                       class="text-[#4EA87D] hover:text-[#3B8361] font-bold inline-flex items-center gap-1 hover:underline text-[11px]">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Unduh Template (.xlsx)
                    </a>
                </div>
                <ul class="space-y-1.5 text-[11px] text-slate-500 list-disc list-inside">
                    <li>Kolom utama: <strong class="text-slate-700">NAMA LENGKAP</strong>, <strong class="text-slate-700">NISN</strong>, dan <strong class="text-slate-700">ROMBEL KELAS</strong> (contoh: <em>10 RPL 1</em>).</li>
                    <li>Siswa masuk ke aplikasi dengan <strong class="text-slate-700">NISN</strong> dan kata sandi bawaan <strong class="text-[#4EA87D]">12345678</strong>.</li>
                    <li><strong class="text-slate-700">Nomor Absen Otomatis:</strong> Sistem langsung mengurutkan nomor absen (1, 2, 3...) per kelas berdasarkan abjad nama.</li>
                </ul>
            </div>

            <form id="formImportExcel" method="POST" action="{{ route('guru.siswa.import_excel') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <!-- Area Unggah Berkas -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Pilih File Spreadsheet (.xlsx, .xls, .csv)</label>
                    <label for="excelFileInput" 
                           id="excelDropzone"
                           class="relative border-2 border-dashed border-slate-200 hover:border-[#4EA87D] bg-slate-50/60 hover:bg-emerald-50/20 rounded-2xl p-6 text-center transition-all cursor-pointer flex flex-col items-center justify-center gap-2 group">
                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 group-hover:border-emerald-300 group-hover:bg-emerald-50 text-slate-400 group-hover:text-emerald-600 flex items-center justify-center transition-all shadow-xs">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 group-hover:text-emerald-700 transition-colors" id="excelFileNameText">
                                Klik untuk memilih file Excel atau seret berkas ke sini
                            </p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Mendukung format .xlsx, .xls, .csv hingga maksimal 5 MB</p>
                        </div>
                        <input type="file" 
                               name="file" 
                               id="excelFileInput" 
                               accept=".xlsx,.xls,.csv" 
                               required 
                               class="hidden" 
                               onchange="handleExcelFileSelect(this)">
                    </label>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-slate-100">
                    <x-button variant="cancel" type="button" onclick="tutupModalImportExcel()" class="!rounded-xl">
                        Batal
                    </x-button>
                    <button type="submit" 
                            id="btnSubmitImport"
                            class="px-5 py-2.5 rounded-xl bg-[#4EA87D] hover:bg-[#43966E] text-white text-xs font-bold shadow-xs transition-all flex items-center gap-2 cursor-pointer active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Mulai Import Siswa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Konfirmasi Hapus Siswa Terpilih (Bulk Delete) -->
    <div id="modalBulkDelete" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-200">
        <div class="fixed inset-0" onclick="tutupModalBulkDelete()"></div>
        
        <div id="modalBulkDeleteCard" 
             class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden transform scale-95 transition-all duration-200 z-10 p-6 lg:p-7 space-y-4">
            
            <div class="flex items-start gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Hapus Siswa Terpilih</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Penghapusan massal akun siswa terpilih</p>
                </div>
            </div>

            <form id="formBulkDelete" method="POST" action="{{ route('guru.siswa.bulk_destroy') }}" class="space-y-4">
                @csrf
                <!-- Container input hidden ID siswa terpilih diisi via Javascript -->
                <div id="bulkDeleteInputsContainer"></div>

                <p class="text-xs text-slate-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus <strong id="modalBulkCountText" class="text-rose-600 font-extrabold text-sm">0</strong> akun siswa yang telah dipilih?
                </p>

                <div class="p-3.5 rounded-2xl bg-rose-50/80 border border-rose-200/70 text-xs text-rose-700 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <p class="leading-relaxed">
                        Seluruh riwayat nilai, progres modul materi, paket kuis, listening audio, dan capaian game siswa terpilih akan dihapus permanen.
                    </p>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100">
                    <x-button variant="cancel" type="button" onclick="tutupModalBulkDelete()" class="!rounded-xl">
                        Batal
                    </x-button>
                    <x-button variant="delete" type="submit" class="!rounded-xl !bg-rose-600 hover:!bg-rose-700 !text-white !border-transparent !shadow-xs">
                        Ya, Hapus Siswa Terpilih
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Pengendali Centang Masal & Modal Hapus -->
    <script>
        function getSelectedCheckboxes() {
            return document.querySelectorAll('.siswa-item-checkbox:checked');
        }

        function getAllCheckboxes() {
            return document.querySelectorAll('.siswa-item-checkbox');
        }

        function onSiswaCheckboxChange() {
            const total = getAllCheckboxes().length;
            const selected = getSelectedCheckboxes().length;
            const selectAllCb = document.getElementById('selectAllCheckbox');
            const bulkBar = document.getElementById('bulkActionBar');
            const countText = document.getElementById('selectedCountText');
            const floatingCheckIcon = document.getElementById('floatingCheckIcon');
            const floatingCheckText = document.getElementById('floatingCheckText');

            // Update master checkbox di table header
            if (selectAllCb) {
                selectAllCb.checked = selected > 0 && selected === total;
                selectAllCb.indeterminate = selected > 0 && selected < total;
            }

            // Highlight baris tabel yang dicentang
            getAllCheckboxes().forEach(cb => {
                const tr = cb.closest('tr');
                if (tr) {
                    if (cb.checked) {
                        tr.classList.add('bg-[#EAF5EF]/50');
                    } else {
                        tr.classList.remove('bg-[#EAF5EF]/50');
                    }
                }
            });

            // Tampilkan atau sembunyikan floating action bar
            if (selected > 0) {
                if (countText) countText.textContent = selected;
                if (bulkBar) {
                    bulkBar.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
                    bulkBar.classList.add('translate-y-0', 'opacity-100');
                }
                if (floatingCheckIcon) {
                    if (selected === total) {
                        floatingCheckIcon.innerHTML = '✓';
                        floatingCheckIcon.classList.add('bg-[#4EA87D]', 'text-white', 'border-[#4EA87D]');
                        floatingCheckText.textContent = 'Lepas Semua (' + total + ')';
                    } else {
                        floatingCheckIcon.innerHTML = '✓';
                        floatingCheckIcon.classList.remove('bg-[#4EA87D]', 'text-white', 'border-[#4EA87D]');
                        floatingCheckText.textContent = 'Pilih Semua (' + total + ')';
                    }
                }
            } else {
                if (bulkBar) {
                    bulkBar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
                    bulkBar.classList.remove('translate-y-0', 'opacity-100');
                }
            }
        }

        function togglePilihSemua(masterCb) {
            const allCb = getAllCheckboxes();
            allCb.forEach(cb => {
                cb.checked = masterCb.checked;
            });
            onSiswaCheckboxChange();
        }

        function togglePilihSemuaSemua() {
            const total = getAllCheckboxes().length;
            const selected = getSelectedCheckboxes().length;
            const shouldSelectAll = selected < total;
            
            getAllCheckboxes().forEach(cb => {
                cb.checked = shouldSelectAll;
            });
            onSiswaCheckboxChange();
        }

        function batalPilihSemua() {
            getAllCheckboxes().forEach(cb => {
                cb.checked = false;
            });
            const selectAllCb = document.getElementById('selectAllCheckbox');
            if (selectAllCb) {
                selectAllCb.checked = false;
                selectAllCb.indeterminate = false;
            }
            onSiswaCheckboxChange();
        }

        function bukaModalBulkDelete() {
            const selected = getSelectedCheckboxes();
            if (selected.length === 0) {
                alert('Silakan pilih minimal 1 siswa untuk dihapus.');
                return;
            }

            const modal = document.getElementById('modalBulkDelete');
            const card = document.getElementById('modalBulkDeleteCard');
            const countText = document.getElementById('modalBulkCountText');
            const container = document.getElementById('bulkDeleteInputsContainer');

            if (countText) countText.textContent = selected.length;
            if (container) {
                container.innerHTML = '';
                selected.forEach(cb => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'siswa_ids[]';
                    hiddenInput.value = cb.value;
                    container.appendChild(hiddenInput);
                });
            }

            if (modal && card) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }
        }

        function tutupModalBulkDelete() {
            const modal = document.getElementById('modalBulkDelete');
            const card = document.getElementById('modalBulkDeleteCard');
            if (modal && card) {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.classList.remove('opacity-100');
                card.classList.add('scale-95');
                card.classList.remove('scale-100');
            }
        }

        // ==========================================
        // Logika Modal Import Data Siswa dari Excel
        // ==========================================
        function bukaModalImportExcel() {
            const modal = document.getElementById('modalImportExcel');
            const card = document.getElementById('modalImportExcelCard');
            if (modal && card) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }
        }

        function tutupModalImportExcel() {
            const modal = document.getElementById('modalImportExcel');
            const card = document.getElementById('modalImportExcelCard');
            if (modal && card) {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.classList.remove('opacity-100');
                card.classList.add('scale-95');
                card.classList.remove('scale-100');
            }
        }

        function handleExcelFileSelect(input) {
            const textSpan = document.getElementById('excelFileNameText');
            const dropzone = document.getElementById('excelDropzone');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSizeKb = (file.size / 1024).toFixed(1);
                textSpan.innerHTML = '<span class="text-emerald-700 font-bold">' + file.name + '</span> <span class="text-slate-400">(' + fileSizeKb + ' KB)</span>';
                if (dropzone) {
                    dropzone.classList.remove('border-slate-200');
                    dropzone.classList.add('border-emerald-500', 'bg-emerald-50/40');
                }
            }
        }
    </script>
</x-layout>
