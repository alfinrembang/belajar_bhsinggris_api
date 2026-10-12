<x-layout 
    title="Tambah Siswa Baru - Study English Prima" 
    pageTitle="Tambah Siswa Baru" 
    pageSubtitle="Daftarkan akun siswa baru untuk dapat mengakses materi, listening, dan kuis di aplikasi mobile." 
    active="siswa"
>
    <x-alert-errors />

    <div class="max-w-4xl bg-white rounded-3xl border border-slate-100 shadow-xs p-6 lg:p-8">
        <x-section-header 
            title="Formulir Identitas & Akun Siswa"
            subtitle="Pastikan NISN, email, dan pembagian rombel kelas siswa sesuai dengan data Dapodik."
            icon="👨‍🎓"
        />

        <form action="{{ route('guru.siswa.store') }}" method="POST" class="mt-6 space-y-6">
            @csrf

            <!-- 1. Data Personal Siswa -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Lengkap Siswa <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_lengkap" 
                           value="{{ old('nama_lengkap') }}" 
                           placeholder="Contoh: Budi Pratama" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Alamat Email Siswa <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="Contoh: budi.pratama@sekolah.sch.id" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Digunakan siswa untuk login ke aplikasi mobile.</p>
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
                           value="{{ old('nis') }}" 
                           placeholder="Contoh: 102938" 
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        NISN Siswa
                    </label>
                    <input type="text" 
                           name="nisn" 
                           value="{{ old('nisn') }}" 
                           placeholder="Contoh: 0071829301" 
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
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
                        <select name="kelas" required class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:border-[#1E6BFF]">
                            <option value="">Pilih Tingkat</option>
                            @foreach($daftarTingkat as $t)
                                <option value="{{ $t }}" {{ old('kelas') == $t ? 'selected' : '' }}>Kelas {{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jurusan -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                            Jurusan <span class="text-rose-500">*</span>
                        </label>
                        <select name="jurusan" required class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:border-[#1E6BFF]">
                            <option value="">Pilih Jurusan</option>
                            @foreach($daftarJurusan as $j)
                                <option value="{{ $j }}" {{ old('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nomor Rombel -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1.5">
                            Nomor Rombel
                        </label>
                        <select name="no_kelas" class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 outline-none focus:border-[#1E6BFF]">
                            <option value="">Default (1)</option>
                            @foreach($daftarNoKelas as $nk)
                                <option value="{{ $nk }}" {{ old('no_kelas', '1') == $nk ? 'selected' : '' }}>Rombel {{ $nk }}</option>
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
                               value="{{ old('no_absen') }}" 
                               placeholder="Contoh: 14" 
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 outline-none focus:border-[#1E6BFF]">
                    </div>
                </div>
            </div>

            <!-- 4. Kata Sandi Bawaan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kata Sandi Akun Siswa (Opsional)
                </label>
                <input type="password" 
                       name="password" 
                       placeholder="Biarkan kosong untuk menggunakan sandi bawaan: 12345678" 
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                <p class="text-[11px] text-slate-400 mt-1">Siswa dapat memperbarui kata sandi ini sewaktu-waktu melalui menu Profil di aplikasi.</p>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('guru.siswa.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white text-xs font-semibold shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all">
                    Simpan Siswa Baru
                </button>
            </div>
        </form>
    </div>
</x-layout>
