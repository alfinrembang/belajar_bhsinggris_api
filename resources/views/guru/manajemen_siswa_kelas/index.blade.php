<x-layout 
    title="Manajemen Siswa & Kelas - Study English Prima" 
    pageTitle="Manajemen Siswa & Kelas" 
    pageSubtitle="Kelola data akun siswa, distribusi rombel kelas, pantau keaktifan belajar, dan reset kata sandi." 
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

    <!-- Card Master Sakelar Periode Kenaikan Kelas & Tahun Ajaran -->
    <div class="bg-white rounded-3xl border {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'border-emerald-200 ring-2 ring-emerald-500/10' : 'border-slate-100' }} shadow-xs p-5 lg:p-6 transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-2xl shrink-0">
                    {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? '🔓' : '🔒' }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 class="font-extrabold text-slate-800 text-base">
                            Periode Kenaikan Kelas — Tahun Ajaran <span class="text-[#1E6BFF]">{{ $pengaturanAkademik->tahun_ajaran }}</span>
                        </h2>
                        @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 animate-pulse">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> PERIODE DIBUKA
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span> TERKUNCI (NONAKTIF)
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                        @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                            Siswa dapat memperbarui rombel kelas dan nomor absen mereka langsung melalui menu profil di aplikasi HP. Jika sudah selesai, silakan tutup kembali periode agar data terkunci.
                        @else
                            Siswa <span class="font-semibold text-slate-700">tidak dapat</span> mengubah rombel kelas dari aplikasi. Jika siswa salah masuk kelas, guru dapat mengoreksinya melalui tombol Edit di tabel bawah.
                        @endif
                    </p>
                    <div class="flex items-center gap-4 mt-2.5 text-[11px] text-slate-400">
                        <span>🗓️ Tahun ajaran otomatis berganti setiap bulan Juli</span>
                        @if($pengaturanAkademik->opened_at && $pengaturanAkademik->is_periode_kenaikan_aktif)
                            <span>• Dibuka: {{ $pengaturanAkademik->opened_at->diffForHumans() }}</span>
                        @elseif($pengaturanAkademik->closed_at)
                            <span>• Terakhir ditutup: {{ $pengaturanAkademik->closed_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Toggle Buka/Tutup Sakelar -->
            <form id="formToggleKenaikan" action="{{ route('guru.siswa.toggle_kenaikan') }}" method="POST" class="shrink-0">
                @csrf
                @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                    <button type="button" 
                            onclick="openPromotionModal()" 
                            class="w-full sm:w-auto px-5 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 font-bold text-xs transition-all flex items-center justify-center gap-2 shadow-xs cursor-pointer active:scale-95">
                        <svg class="w-4 h-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        Tutup Periode Kenaikan Kelas
                    </button>
                @else
                    <button type="button" 
                            onclick="openPromotionModal()" 
                            class="w-full sm:w-auto px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-[0_6px_16px_rgba(16,185,129,0.28)] transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
                        </svg>
                        Buka Periode Kenaikan Kelas
                    </button>
                @endif
            </form>
        </div>
    </div>

    <!-- Bar Aksi Atas: Pencarian, Filter Kelas/Jurusan & Tombol Tambah Siswa -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs">
        <form method="GET" action="{{ route('guru.siswa.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto flex-1">
            <!-- Pencarian Siswa -->
            <div class="relative flex-1 max-w-md">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari nama siswa, NIS, NISN, atau email..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
            </div>

            <!-- Filter Tingkat Kelas -->
            <select name="kelas" onchange="this.form.submit()" class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="">Semua Tingkat</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>

            <!-- Filter Jurusan -->
            <select name="jurusan" onchange="this.form.submit()" class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="">Semua Jurusan</option>
                @foreach($daftarJurusan as $jur)
                    <option value="{{ $jur }}" {{ request('jurusan') == $jur ? 'selected' : '' }}>{{ $jur }}</option>
                @endforeach
            </select>

            @if(request('q') || request('kelas') || request('jurusan'))
                <a href="{{ route('guru.siswa.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-rose-600 transition-colors flex items-center gap-1 self-center">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Reset Filter
                </a>
            @endif
        </form>

        <!-- Tombol Tambah Siswa Baru -->
        <a href="{{ route('guru.siswa.create') }}" 
           class="px-5 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-semibold text-xs shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all flex items-center gap-2 shrink-0 self-start sm:self-auto">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Siswa Baru
        </a>
    </div>

    <!-- Ringkasan Statistik Siswa & Kelas (Reusable Component) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <x-stat-card title="Total Siswa Terdaftar" :value="$totalSiswa . ' Siswa'" icon="👨‍🎓" color="blue" />
        <x-stat-card title="Rombel / Kelas Aktif" :value="$totalRombel . ' Rombel'" icon="🏫" color="emerald" />
        <x-stat-card title="Siswa Aktif Belajar" :value="$siswaAktif . ' Siswa'" icon="⚡" color="purple" />
    </div>

    <!-- Tabel Daftar Siswa -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Daftar Akun Siswa & Distribusi Kelas</h3>
                <p class="text-xs text-slate-400 mt-0.5">Pantau identitas, nomor absen, dan akses buku rapor nilai</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 bg-slate-100 rounded-full text-slate-600">
                Total: {{ $daftarSiswa->total() }} Siswa
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">No</th>
                        <th class="py-3.5 px-6">Nama Siswa & Email</th>
                        <th class="py-3.5 px-6">NIS / NISN</th>
                        <th class="py-3.5 px-6">Rombel Kelas</th>
                        <th class="py-3.5 px-6 text-center">No. Absen</th>
                        <th class="py-3.5 px-6 text-center">Status Akun</th>
                        <th class="py-3.5 px-6 text-center">Aksi & Rapor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($daftarSiswa as $index => $siswa)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-slate-400 font-semibold">
                                {{ $daftarSiswa->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($siswa->nama_lengkap ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('guru.siswa.show', $siswa) }}" class="font-bold text-slate-800 text-sm hover:text-[#1E6BFF] transition-colors">
                                            {{ $siswa->nama_lengkap }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 mt-0.5 font-normal">{{ $siswa->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-mono text-slate-600">
                                <div>
                                    <span class="font-semibold text-slate-700">{{ $siswa->nis ?: '-' }}</span>
                                    @if($siswa->nisn)
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-normal">NISN: {{ $siswa->nisn }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="space-y-1">
                                    <span class="px-3 py-1 rounded-full bg-blue-50 text-[#1E6BFF] font-semibold text-xs inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#1E6BFF]"></span>
                                        {{ $siswa->kelas_lengkap ?: 'Belum diatur' }}
                                    </span>
                                    <div class="text-[10px] text-slate-400 font-mono">
                                        TA: {{ $siswa->tahun_ajaran_aktif ?: $pengaturanAkademik->tahun_ajaran }}
                                        @if($siswa->status_kelulusan === 'tinggal_kelas')
                                            <span class="text-amber-600 font-bold ml-1">(Mengulang)</span>
                                        @elseif($siswa->status_kelulusan === 'lulus')
                                            <span class="text-emerald-600 font-bold ml-1">(Lulus)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-slate-600">
                                {{ $siswa->no_absen ?: '-' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($siswa->api_token)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif di HP
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[11px] font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Terdaftar
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- 1. Tombol Transkrip Rapor Nilai -->
                                    <a href="{{ route('guru.siswa.show', $siswa) }}" 
                                       class="px-2.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-[#1E6BFF] font-semibold text-[11px] transition-colors flex items-center gap-1"
                                       title="Buka Transkrip Nilai Siswa">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        Transkrip Nilai
                                    </a>

                                    <!-- 2. Tombol Reset Password Cepat -->
                                    <form action="{{ route('guru.siswa.reset_password', $siswa) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Reset kata sandi siswa &quot;{{ addslashes($siswa->nama_lengkap) }}&quot; menjadi default &quot;12345678&quot;?')" 
                                          class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" 
                                                title="Reset Password ke: 12345678">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- 3. Tombol Edit -->
                                    <a href="{{ route('guru.siswa.edit', $siswa) }}" 
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-[#1E6BFF] hover:bg-blue-50 transition-colors" 
                                       title="Edit Data Siswa">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    <!-- 4. Tombol Hapus -->
                                    <form action="{{ route('guru.siswa.destroy', $siswa) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun siswa &quot;{{ addslashes($siswa->nama_lengkap) }}&quot; beserta seluruh riwayat belajarnya?')" 
                                          class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" 
                                                title="Hapus Akun Siswa">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-empty-state 
                            colspan="7" 
                            icon="👨‍🎓" 
                            title="Belum ada data siswa ditemukan" 
                            subtitle="Silakan daftarkan akun siswa baru atau ubah kata kunci filter pencarian Anda."
                            actionText="Daftarkan Siswa Baru"
                            :actionUrl="route('guru.siswa.create')"
                        />
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

    <!-- Modal Konfirmasi Buka/Tutup Periode Kenaikan Kelas (Pop Up Custom) -->
    <div id="promotionModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300"
         role="dialog" 
         aria-modal="true">
        
        <div id="promotionModalCard" 
             class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform scale-95 transition-all duration-300">
            
            <!-- Tombol Close (X) -->
            <button type="button" 
                    onclick="closePromotionModal()" 
                    class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-colors z-10 cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <!-- Bagian Atas: Background Header & Ikon Dinamis -->
            <div class="p-6 sm:p-7 {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'bg-gradient-to-br from-rose-50/80 via-white to-white' : 'bg-gradient-to-br from-emerald-50/80 via-white to-white' }}">
                <div class="flex items-start gap-4">
                    <!-- Icon Avatar dengan Glowing Ring -->
                    @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                        <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl shrink-0 ring-8 ring-rose-50 shadow-inner">
                            🔒
                        </div>
                    @else
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl shrink-0 ring-8 ring-emerald-50 shadow-inner">
                            🔓
                        </div>
                    @endif

                    <div class="flex-1 pr-6">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }} mb-2">
                            <span>🗓️</span> Tahun Ajaran {{ $pengaturanAkademik->tahun_ajaran }}
                        </div>

                        <h3 class="text-lg sm:text-xl font-extrabold text-slate-800 leading-snug">
                            @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                                Tutup Periode Kenaikan Kelas?
                            @else
                                Buka Periode Kenaikan Kelas?
                            @endif
                        </h3>

                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                                Rombel kelas dan nomor absen di aplikasi siswa akan <span class="font-bold text-slate-700">dikunci kembali</span> (*read-only*). Siswa tidak dapat mengubah kelasnya secara mandiri lagi.
                            @else
                                Siswa di aplikasi mobile akan dapat <span class="font-bold text-slate-700">memperbarui tingkat kelas, rombel acak, dan nomor absen</span> mereka melalui menu profil.
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Kotak Informasi Detail / Benefit -->
                <div class="mt-5 p-4 rounded-2xl {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'bg-slate-50 border border-slate-200/80' : 'bg-emerald-50/70 border border-emerald-100' }} space-y-2.5">
                    @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                        <div class="flex items-start gap-2.5 text-xs text-slate-600">
                            <span class="w-4 h-4 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">✓</span>
                            <p><span class="font-bold text-slate-800">Cegah Perubahan Liar:</span> Rombel siswa dipatenkan dan tidak dapat diubah sepihak oleh siswa.</p>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-slate-600">
                            <span class="w-4 h-4 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">✓</span>
                            <p><span class="font-bold text-slate-800">Koreksi Manual Guru:</span> Jika ada siswa salah memilih kelas, guru tetap dapat memperbaikinya lewat tombol Edit di tabel.</p>
                        </div>
                    @else
                        <div class="flex items-start gap-2.5 text-xs text-emerald-900">
                            <span class="w-4 h-4 rounded-full bg-emerald-200 text-emerald-800 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">✓</span>
                            <p><span class="font-bold text-emerald-950">Jurusan Tetap Terkunci:</span> Siswa hanya bisa berganti rombel di jurusannya sendiri (misal TSM tetap TSM).</p>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-emerald-900">
                            <span class="w-4 h-4 rounded-full bg-emerald-200 text-emerald-800 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">✓</span>
                            <p><span class="font-bold text-emerald-950">Nilai & XP 100% Aman:</span> Seluruh riwayat latihan materi, listening, dan kuis tetap tersimpan tanpa hilang.</p>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-emerald-900">
                            <span class="w-4 h-4 rounded-full bg-emerald-200 text-emerald-800 flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">✓</span>
                            <p><span class="font-bold text-emerald-950">Kendali Penuh Guru:</span> Guru dapat menutup kembali sakelar ini kapan saja setelah siswa selesai memperbarui data.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tombol Aksi Bawah -->
            <div class="p-5 sm:px-7 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" 
                        onclick="closePromotionModal()" 
                        class="px-5 py-2.5 rounded-2xl border border-slate-200/90 bg-white hover:bg-slate-100 text-slate-600 font-bold text-xs transition-colors cursor-pointer shadow-xs">
                    Batal
                </button>

                @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                    <button type="button" 
                            id="btnSubmitPromotion"
                            onclick="submitPromotionForm()" 
                            class="px-6 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-[0_6px_20px_rgba(225,29,72,0.3)] transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <span>Ya, Tutup Periode Sekarang</span>
                    </button>
                @else
                    <button type="button" 
                            id="btnSubmitPromotion"
                            onclick="submitPromotionForm()" 
                            class="px-6 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-[0_6px_20px_rgba(16,185,129,0.3)] transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
                        </svg>
                        <span>Ya, Buka Periode Sekarang</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Script Interaktif Modal -->
    <script>
        function openPromotionModal() {
            const modal = document.getElementById('promotionModal');
            const card = document.getElementById('promotionModalCard');
            if (!modal || !card) return;

            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');

            card.classList.remove('scale-95');
            card.classList.add('scale-100');

            document.body.classList.add('overflow-hidden');
        }

        function closePromotionModal() {
            const modal = document.getElementById('promotionModal');
            const card = document.getElementById('promotionModalCard');
            if (!modal || !card) return;

            modal.classList.remove('opacity-100', 'pointer-events-auto');
            modal.classList.add('opacity-0', 'pointer-events-none');

            card.classList.remove('scale-100');
            card.classList.add('scale-95');

            document.body.classList.remove('overflow-hidden');
        }

        function submitPromotionForm() {
            const btn = document.getElementById('btnSubmitPromotion');
            const form = document.getElementById('formToggleKenaikan');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Memproses...</span>
                `;
            }
            if (form) {
                form.submit();
            }
        }

        // Tutup modal jika klik di luar modal card
        document.getElementById('promotionModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closePromotionModal();
            }
        });

        // Tutup modal jika tombol ESC ditekan
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePromotionModal();
            }
        });
    </script>
</x-layout>
