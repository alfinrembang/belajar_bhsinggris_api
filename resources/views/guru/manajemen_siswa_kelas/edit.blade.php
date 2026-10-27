<x-layout 
    title="Edit Siswa - Study English Prima" 
    pageTitle="Edit Data Siswa" 
    pageSubtitle="Perbarui informasi akun, pembagian rombel kelas, atau reset kata sandi siswa." 
    active="siswa"
>
    <!-- Navigasi Kembali & Aksi Cepat -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-button variant="back" href="{{ route('guru.siswa.index') }}">
            Kembali ke Manajemen Siswa & Kelas
        </x-button>

        <x-button variant="show" href="{{ route('guru.siswa.show', $siswa) }}">
            Lihat Transkrip Siswa
        </x-button>
    </div>

    <x-alert-errors />

    <div class="max-w-4xl bg-white rounded-3xl border border-slate-100 shadow-xs p-6 lg:p-8">
        <x-section-header 
            title="Formulir Pembaruan Data Siswa"
            subtitle="Perubahan kelas atau identitas akan langsung tersinkronisasi ke aplikasi mobile siswa."
        />

        <form action="{{ route('guru.siswa.update', $siswa) }}" method="POST" class="mt-6 space-y-6">
            @csrf

            <!-- 1. Data Personal Siswa -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Lengkap Siswa <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_lengkap" 
                           value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Alamat Email Siswa <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $siswa->email) }}" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                </div>
            </div>

            <!-- 2. NIS & NISN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nomor Induk Siswa (NIS)
                    </label>
                    <input type="text" 
                           name="nis" 
                           value="{{ old('nis', $siswa->nis) }}" 
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        NISN Siswa
                    </label>
                    <input type="text" 
                           name="nisn" 
                           value="{{ old('nisn', $siswa->nisn) }}" 
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                </div>
            </div>

            <!-- 3. Rombel Kelas & No Absen -->
            <div class="p-5 rounded-2xl bg-slate-50/75 border border-slate-100 space-y-4">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <span>🏫</span> Distribusi Rombel Kelas & Presensi
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <!-- Tingkat Kelas -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                            Tingkat Kelas <span class="text-rose-500">*</span>
                        </label>
                        <select name="kelas" required class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:border-[#4EA87D]">
                            @foreach($daftarTingkat as $t)
                                <option value="{{ $t }}" {{ old('kelas', $siswa->kelas) == $t ? 'selected' : '' }}>Kelas {{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jurusan -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                            Jurusan <span class="text-rose-500">*</span>
                        </label>
                        <select name="jurusan" required class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:border-[#4EA87D]">
                            @foreach($daftarJurusan as $j)
                                <option value="{{ $j }}" {{ old('jurusan', $siswa->jurusan) == $j ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nomor Rombel -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                            Nomor Rombel
                        </label>
                        <select name="no_kelas" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:border-[#4EA87D]">
                            @foreach($daftarNoKelas as $nk)
                                <option value="{{ $nk }}" {{ old('no_kelas', $siswa->no_kelas) == $nk ? 'selected' : '' }}>Rombel {{ $nk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nomor Absen -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                            No. Absen Siswa
                        </label>
                        <input type="text" 
                               name="no_absen" 
                               value="{{ old('no_absen', $siswa->no_absen) }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 outline-none focus:border-[#4EA87D]">
                    </div>
                </div>
            </div>

            <!-- 3B. Tahun Ajaran & Status Kelulusan Siswa -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tahun Ajaran Aktif Siswa
                    </label>
                    <input type="text" 
                           name="tahun_ajaran_aktif" 
                           value="{{ old('tahun_ajaran_aktif', $siswa->tahun_ajaran_aktif ?? '2026/2027') }}" 
                           placeholder="Contoh: 2026/2027"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Status Akademik / Kelulusan
                    </label>
                    <select name="status_kelulusan" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:border-[#4EA87D]">
                        <option value="aktif" {{ old('status_kelulusan', $siswa->status_kelulusan) == 'aktif' ? 'selected' : '' }}>Aktif Bersekolah</option>
                        <option value="tinggal_kelas" {{ old('status_kelulusan', $siswa->status_kelulusan) == 'tinggal_kelas' ? 'selected' : '' }}>Tinggal Kelas (Mengulang)</option>
                        <option value="lulus" {{ old('status_kelulusan', $siswa->status_kelulusan) == 'lulus' ? 'selected' : '' }}>Alumni / Telah Lulus</option>
                    </select>
                </div>
            </div>

            <!-- 4. Ganti Kata Sandi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Ganti Kata Sandi (Opsional)
                </label>
                <input type="password" 
                       name="password" 
                       placeholder="Biarkan kosong jika tidak ingin mengubah kata sandi siswa" 
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika kata sandi siswa tidak mengalami perubahan.</p>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <x-button variant="cancel" href="{{ route('guru.siswa.index') }}">
                    Batal
                </x-button>
                <x-button variant="submit">
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</x-layout>
