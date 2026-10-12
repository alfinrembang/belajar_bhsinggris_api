<x-layout 
    title="Pengaturan Sistem - Study English Prima" 
    pageTitle="Pengaturan Sistem" 
    pageSubtitle="Konfigurasi akun pengajar, kalender akademik, dan preferensi evaluasi pembelajaran." 
    active="pengaturan"
>
    <!-- Flash Messages & Alert Notifikasi -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-between text-xs text-emerald-800 shadow-xs animate-fade-in">
            <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">✓</span>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold p-1 cursor-pointer">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-xs text-rose-800 shadow-xs">
            <div class="flex items-center gap-2 font-bold mb-1">
                <span>⚠️</span> Terdapat beberapa kesalahan input:
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-700 font-medium pl-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri (1 Kolom): Profil Pengajar Ringkas & Info Platform -->
        <div class="space-y-6">
            <!-- Kartu Profil Guru -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs text-center">
                <div class="w-24 h-24 mx-auto rounded-3xl bg-gradient-to-br from-[#1E6BFF] to-[#0A56E2] text-white flex items-center justify-center font-bold text-3xl shadow-[0_8px_20px_rgba(30,107,255,0.3)] mb-4">
                    {{ strtoupper(substr($guru->name ?? 'G', 0, 1)) }}
                </div>
                <h3 class="font-extrabold text-slate-800 text-base">{{ $guru->name ?? 'Guru Pengajar' }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ $guru->email ?? 'guru@mail.com' }}</p>
                @if($guru->nip)
                    <p class="text-[11px] font-mono text-slate-500 mt-1 bg-slate-100 inline-block px-2.5 py-0.5 rounded-md">
                        NIP: {{ $guru->nip }}
                    </p>
                @endif

                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 text-[#1E6BFF] text-xs font-semibold capitalize">
                        <svg class="w-3.5 h-3.5 text-[#1E6BFF]" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                        </svg>
                        Role: {{ $guru->role ?? 'guru' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Aktif
                    </span>
                </div>
            </div>

            <!-- Kartu Status Aplikasi & Server -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs space-y-3">
                <h4 class="font-extrabold text-slate-800 text-xs uppercase tracking-wider text-slate-400">Informasi Platform</h4>
                
                <div class="flex items-center justify-between text-xs py-2 border-b border-slate-100">
                    <span class="text-slate-500">Versi Portal Web</span>
                    <span class="font-bold text-slate-800">v1.2.0</span>
                </div>
                <div class="flex items-center justify-between text-xs py-2 border-b border-slate-100">
                    <span class="text-slate-500">Laravel Framework</span>
                    <span class="font-bold text-slate-800">{{ app()->version() }}</span>
                </div>
                <div class="flex items-center justify-between text-xs py-2 border-b border-slate-100">
                    <span class="text-slate-500">Status Server</span>
                    <span class="inline-flex items-center gap-1.5 font-bold text-emerald-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Online
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs py-2">
                    <span class="text-slate-500">Aplikasi Siswa (Flutter)</span>
                    <span class="font-bold text-blue-600">REST API v1</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan (2 Kolom): Form Konfigurasi Profil, Akademik & Keamanan -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 1. Form Profil Guru -->
            <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-sm">Informasi Akun Pengajar</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Perbarui nama lengkap, email login, dan NIP Anda</p>
                    </div>
                    <span class="p-2.5 rounded-2xl bg-blue-50 text-[#1E6BFF] text-base">👤</span>
                </div>

                <form action="{{ route('guru.pengaturan.update_profil') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap & Gelar</label>
                            <input type="text" 
                                   name="name"
                                   required
                                   value="{{ old('name', $guru->name) }}" 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Pengajar</label>
                            <input type="email" 
                                   name="email"
                                   required
                                   value="{{ old('email', $guru->email) }}" 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">NIP / NUPTK (Opsional)</label>
                        <input type="text" 
                               name="nip"
                               placeholder="Contoh: 198507202010011012"
                               value="{{ old('nip', $guru->nip) }}" 
                               class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-bold text-xs shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all cursor-pointer">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. Form Konfigurasi Akademik & Preferensi Evaluasi -->
            <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-sm">Konfigurasi Akademik & Evaluasi</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Pengaturan tahun ajaran aktif, KKM, dan sakelar fitur aplikasi siswa</p>
                    </div>
                    <span class="p-2.5 rounded-2xl bg-purple-50 text-purple-600 text-base">⚙️</span>
                </div>

                <form action="{{ route('guru.pengaturan.update_akademik') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Ajaran Aktif</label>
                            <input type="text" 
                                   name="tahun_ajaran"
                                   required
                                   placeholder="2026/2027"
                                   value="{{ old('tahun_ajaran', $pengaturanAkademik->tahun_ajaran) }}" 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                            <span class="text-[10px] text-slate-400 mt-1 block">Format: YYYY/YYYY</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Standar KKM Kelulusan</label>
                            <input type="number" 
                                   name="kkm_default"
                                   required
                                   min="0"
                                   max="100"
                                   value="{{ old('kkm_default', $pengaturanAkademik->kkm_default ?? 75) }}" 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                            <span class="text-[10px] text-slate-400 mt-1 block">Batas tuntas evaluasi</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Durasi Kuis Default</label>
                            <input type="number" 
                                   name="durasi_kuis_default"
                                   required
                                   min="5"
                                   max="180"
                                   value="{{ old('durasi_kuis_default', $pengaturanAkademik->durasi_kuis_default ?? 20) }}" 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                            <span class="text-[10px] text-slate-400 mt-1 block">Dalam satuan menit</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan Catatan Akademik (Opsional)</label>
                        <input type="text" 
                               name="keterangan"
                               placeholder="Contoh: Semester Ganjil TA 2026/2027"
                               value="{{ old('keterangan', $pengaturanAkademik->keterangan) }}" 
                               class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                    </div>

                    <!-- Sakelar 1: Kenaikan Kelas Siswa -->
                    <div class="flex items-center justify-between p-4 rounded-2xl {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'bg-blue-50/70 border border-blue-200' : 'bg-slate-50 border border-slate-200/70' }} transition-colors">
                        <div class="pr-4">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-xs">Periode Kenaikan Kelas & Kelulusan Siswa</span>
                                @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-extrabold">DIBUKA</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 text-[10px] font-extrabold">TERKUNCI</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Jika diaktifkan, siswa di aplikasi mobile diberikan izin memperbarui kelas dan nomor absen di profil mereka.
                            </p>
                        </div>
                        <input type="checkbox" 
                               name="is_periode_kenaikan_aktif" 
                               value="1" 
                               {{ old('is_periode_kenaikan_aktif', $pengaturanAkademik->is_periode_kenaikan_aktif) ? 'checked' : '' }} 
                               class="w-5 h-5 text-[#1E6BFF] rounded accent-[#1E6BFF] cursor-pointer shrink-0">
                    </div>

                    <!-- Sakelar 2: Papan Peringkat (Leaderboard) -->
                    <div class="flex items-center justify-between p-4 rounded-2xl {{ $pengaturanAkademik->is_leaderboard_aktif ? 'bg-purple-50/70 border border-purple-200' : 'bg-slate-50 border border-slate-200/70' }} transition-colors">
                        <div class="pr-4">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-xs">Papan Peringkat (Leaderboard) Siswa</span>
                                @if($pengaturanAkademik->is_leaderboard_aktif)
                                    <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-extrabold">AKTIF</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 text-[10px] font-extrabold">NONAKTIF</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Mengontrol apakah papan peringkat perolehan XP siswa ditampilkan di aplikasi mobile.
                            </p>
                        </div>
                        <input type="checkbox" 
                               name="is_leaderboard_aktif" 
                               value="1" 
                               {{ old('is_leaderboard_aktif', $pengaturanAkademik->is_leaderboard_aktif ?? true) ? 'checked' : '' }} 
                               class="w-5 h-5 text-purple-600 rounded accent-purple-600 cursor-pointer shrink-0">
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition-all cursor-pointer">
                            Simpan Pengaturan Akademik
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. Form Ganti Kata Sandi Guru -->
            <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-sm">Keamanan & Kata Sandi</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Ubah kata sandi akun login portal pengajar</p>
                    </div>
                    <span class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 text-base">🔒</span>
                </div>

                <form action="{{ route('guru.pengaturan.update_password') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Saat Ini</label>
                        <input type="password" 
                               name="current_password"
                               required
                               placeholder="Masukkan kata sandi lama Anda..." 
                               class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                            <input type="password" 
                                   name="password"
                                   required
                                   placeholder="Minimal 8 karakter..." 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" 
                                   name="password_confirmation"
                                   required
                                   placeholder="Ulangi kata sandi baru..." 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] outline-none">
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-[0_6px_16px_rgba(217,119,6,0.25)] transition-all cursor-pointer">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</x-layout>
