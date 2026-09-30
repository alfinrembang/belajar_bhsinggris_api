<x-layout 
    title="Modul Listening - Study English Prima" 
    pageTitle="Modul Listening" 
    pageSubtitle="Kelola rekaman audio percakapan, transkrip cerita, dan latihan listening siswa." 
    active="listening"
>
    <!-- Bar Aksi Atas: Pencarian & Tombol Tambah Audio -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs">
        <!-- Pencarian Audio Listening -->
        <div class="relative w-full md:w-80">
            <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" 
                   placeholder="Cari topik atau audio listening..." 
                   class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
        </div>

        <!-- Filter & Tombol Unggah -->
        <div class="flex items-center gap-3">
            <select class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="">Semua Tingkat</option>
                <option value="beginner">Beginner (Pemula)</option>
                <option value="intermediate">Intermediate (Menengah)</option>
                <option value="advanced">Advanced (Lanjutan)</option>
            </select>

            <!-- Tombol Unggah Audio -->
            <a href="#" 
               class="px-5 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-semibold text-xs shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Unggah Audio Baru
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik Listening -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-lg shrink-0">
                🎧
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Audio</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">6 Track</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                ⏱️
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Rata-rata Durasi</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">2 - 3 Menit</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg shrink-0">
                📝
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Latihan Terkait</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">30 Pertanyaan</h4>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Track Listening -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Daftar Modul Rekaman Audio</h3>
            <span class="text-xs text-slate-400">Sinkron dengan menu listening aplikasi siswa</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Judul Percakapan & Audio</th>
                        <th class="py-3.5 px-6">Tingkat Kesulitan</th>
                        <th class="py-3.5 px-6">Durasi</th>
                        <th class="py-3.5 px-6">Tahap Belajar</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    <!-- Baris 1 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-xs shrink-0">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">At the Airport & Checking In</p>
                                    <p class="text-[11px] text-slate-400">Percakapan penumpang dan petugas bandara</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 rounded-full bg-blue-50 text-[#1E6BFF] text-[11px] font-semibold">Beginner</span>
                        </td>
                        <td class="py-4 px-6 text-slate-600">02:15</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 text-slate-600 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Step 1 - 5 Lengkap
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">Aktif</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button class="p-1.5 rounded-lg text-slate-400 hover:text-[#1E6BFF] hover:bg-blue-50 transition-colors" title="Edit Modul">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <button class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Modul">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Baris 2 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs shrink-0">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">Ordering Food in a Restaurant</p>
                                    <p class="text-[11px] text-slate-400">Dialog memesan menu, menanyakan harga dan pesanan</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-600 text-[11px] font-semibold">Intermediate</span>
                        </td>
                        <td class="py-4 px-6 text-slate-600">03:00</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 text-slate-600 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Step 1 - 5 Lengkap
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">Aktif</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button class="p-1.5 rounded-lg text-slate-400 hover:text-[#1E6BFF] hover:bg-blue-50 transition-colors" title="Edit Modul">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <button class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Modul">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
