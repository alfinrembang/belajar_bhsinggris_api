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
                       class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 outline-none transition-all">
            </div>

            <!-- Filter Tingkat Kelas -->
            <select name="kelas" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-blue-500">
                <option value="">Semua Tingkat</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>

            <!-- Filter Jurusan -->
            <select name="jurusan" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-blue-500">
                <option value="">Semua Jurusan</option>
                @foreach($daftarJurusan as $jur)
                    <option value="{{ $jur }}" {{ request('jurusan') == $jur ? 'selected' : '' }}>{{ $jur }}</option>
                @endforeach
            </select>

            @if(request('q') || request('kelas') || request('jurusan'))
                <a href="{{ route('guru.siswa.index') }}" class="px-3 py-2 text-xs font-medium text-slate-500 hover:text-rose-600 transition-colors flex items-center gap-1 self-center">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Reset
                </a>
            @endif
        </form>

        <!-- Tombol Tambah Siswa Baru -->
        <a href="{{ route('guru.siswa.create') }}" 
           class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-2 shrink-0 self-start sm:self-auto cursor-pointer active:scale-95">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Siswa Baru
        </a>
    </div>

    <!-- Ringkasan Statistik Siswa & Kelas -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Siswa Terdaftar</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 tracking-tight">{{ $totalSiswa }} Siswa</h3>
                <p class="text-[11px] text-blue-600 font-medium mt-1">Siswa Aktif</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-blue-600 flex items-center justify-center shrink-0">
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
                <p class="text-[11px] text-emerald-600 font-medium mt-1">Distribusi Kelas</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-emerald-600 flex items-center justify-center shrink-0">
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
                <p class="text-[11px] text-slate-500 font-medium mt-1">Aktivitas Belajar</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
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
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-slate-400 font-semibold text-center whitespace-nowrap">
                                {{ $daftarSiswa->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0 border border-slate-200/60">
                                        {{ strtoupper(substr($siswa->nama_lengkap ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('guru.siswa.show', $siswa) }}" class="font-semibold text-slate-900 text-xs hover:text-blue-600 transition-colors">
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
                                    <span class="whitespace-nowrap px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-semibold text-xs inline-flex items-center gap-1.5 border border-blue-100/70">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
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
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-slate-100 transition-colors" 
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
                            <td colspan="7" class="py-12 px-6 text-center text-slate-400">
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
                                       class="mt-4 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition-all shadow-xs inline-flex items-center gap-1.5">
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

        @if($daftarSiswa->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $daftarSiswa->links() }}
            </div>
        @endif
    </div>

</x-layout>
