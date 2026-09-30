<x-layout 
    title="Pengaturan Sistem - Study English Prima" 
    pageTitle="Pengaturan Sistem" 
    pageSubtitle="Konfigurasi profil pengajar, preferensi modul pembelajaran, dan keamanan akun." 
    active="pengaturan"
>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri (1 Kolom): Profil Pengajar Ringkas -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs text-center">
                <div class="w-24 h-24 mx-auto rounded-3xl bg-gradient-to-br from-[#1E6BFF] to-[#0A56E2] text-white flex items-center justify-center font-bold text-3xl shadow-[0_8px_20px_rgba(30,107,255,0.3)] mb-4">
                    {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                </div>
                <h3 class="font-bold text-slate-800 text-base">{{ auth()->user()->name ?? 'Guru Pengajar' }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ auth()->user()->email ?? 'guru@mail.com' }}</p>

                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 text-[#1E6BFF] text-xs font-semibold capitalize">
                        <svg class="w-3.5 h-3.5 text-[#1E6BFF]" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                        </svg>
                        Role: {{ auth()->user()->role ?? 'guru' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                </div>
            </div>

            <!-- Kartu Status Aplikasi -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs space-y-3">
                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider text-slate-400">Informasi Platform</h4>
                
                <div class="flex items-center justify-between text-xs py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Versi Portal</span>
                    <span class="font-bold text-slate-800">v1.2.0-beta</span>
                </div>
                <div class="flex items-center justify-between text-xs py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Laravel Framework</span>
                    <span class="font-bold text-slate-800">{{ app()->version() }}</span>
                </div>
                <div class="flex items-center justify-between text-xs py-1.5">
                    <span class="text-slate-500">Aplikasi Mobile Siswa</span>
                    <span class="font-bold text-emerald-600">Terhubung (API v1)</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan (2 Kolom): Form Konfigurasi Profil & Preferensi -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 1. Form Profil Guru -->
            <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Informasi Akun Pengajar</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Perbarui nama dan alamat email login Anda</p>
                    </div>
                    <span class="p-2 rounded-xl bg-blue-50 text-[#1E6BFF]">👤</span>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" 
                                   value="{{ auth()->user()->name ?? 'Guru Pengajar' }}" 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Pengajar</label>
                            <input type="email" 
                                   value="{{ auth()->user()->email ?? 'guru@mail.com' }}" 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="button" 
                                class="px-5 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-semibold text-xs shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>

            <!-- 2. Preferensi Modul Pembelajaran -->
            <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Preferensi Modul Pembelajaran</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Pengaturan standar penilaian dan evaluasi siswa</p>
                    </div>
                    <span class="p-2 rounded-xl bg-purple-50 text-purple-600">⚙️</span>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Standar KKM Kuis (Nilai)</label>
                            <input type="number" 
                                   value="75" 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Durasi Default Kuis (Menit)</label>
                            <input type="number" 
                                   value="20" 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                        </div>
                    </div>

                    <!-- Toggle Peringkat Siswa -->
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50/70 border border-slate-100">
                        <div>
                            <p class="text-xs font-bold text-slate-800">Tampilkan Papan Peringkat di Aplikasi Siswa</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Siswa dapat melihat posisi XP di leaderboard kelas</p>
                        </div>
                        <input type="checkbox" checked class="w-4 h-4 text-[#1E6BFF] rounded accent-[#1E6BFF] cursor-pointer">
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="button" 
                                class="px-5 py-2.5 rounded-2xl bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs transition-all">
                            Perbarui Preferensi
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. Ganti Kata Sandi -->
            <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Keamanan & Kata Sandi</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Ubah kata sandi akun login portal pengajar</p>
                    </div>
                    <span class="p-2 rounded-xl bg-amber-50 text-amber-600">🔒</span>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                            <input type="password" 
                                   placeholder="Minimal 6 karakter..." 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
                            <input type="password" 
                                   placeholder="Ulangi kata sandi baru..." 
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] outline-none">
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="button" 
                                class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-all">
                            Ganti Kata Sandi
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-layout>
