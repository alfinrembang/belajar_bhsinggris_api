<x-layout 
    title="Bank Kosakata - Study English Prima" 
    pageTitle="Bank Kosakata" 
    pageSubtitle="Kelola daftar kosakata bahasa Inggris, arti, pelafalan, dan contoh kalimat." 
    active="kosakata"
>
    <!-- Bar Aksi Atas: Pencarian, Filter & Tombol Tambah Kosakata -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs">
        <!-- Pencarian Kosakata -->
        <div class="relative w-full md:w-80">
            <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" 
                   placeholder="Cari kata Inggris atau arti..." 
                   class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
        </div>

        <!-- Filter & Tombol Tambah -->
        <div class="flex items-center gap-3">
            <select class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#1E6BFF]">
                <option value="">Semua Kategori</option>
                <option value="noun">Noun (Kata Benda)</option>
                <option value="verb">Verb (Kata Kerja)</option>
                <option value="adjective">Adjective (Kata Sifat)</option>
                <option value="adverb">Adverb (Kata Keterangan)</option>
            </select>

            <!-- Tombol Tambah Kosakata -->
            <a href="#" 
               class="px-5 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-semibold text-xs shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Kosakata
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik Kosakata -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-lg shrink-0">
                📖
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Kosakata</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ count($daftarKosakata ?? []) }} Kata</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                💬
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Dengan Contoh</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ collect($daftarKosakata ?? [])->whereNotNull('contoh')->count() }} Kalimat</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg shrink-0">
                ⚡
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Sinkronisasi Siswa</p>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">Otomatis Aktif</h4>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kosakata -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Daftar Bank Kosakata Terdaftar</h3>
            <span class="text-xs text-slate-400">Digunakan pada modul vocabulary siswa</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">No</th>
                        <th class="py-3.5 px-6">Kata Bahasa Inggris</th>
                        <th class="py-3.5 px-6">Terjemahan Indonesia</th>
                        <th class="py-3.5 px-6">Contoh Penggunaan Kalimat</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($daftarKosakata ?? [] as $index => $vocab)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-slate-400 font-semibold">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($vocab->english ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $vocab->english }}</p>
                                        <span class="inline-flex items-center gap-1 text-[11px] text-slate-400">
                                            <svg class="w-3.5 h-3.5 text-[#1E6BFF]" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77M16.5 12c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02M3 9v6h4l5 5V4L7 9H3Z"/>
                                            </svg>
                                            Audio pronunciation
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-xs">
                                    {{ $vocab->indonesia }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-600 italic">
                                {{ $vocab->contoh ?: '-' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="p-1.5 rounded-lg text-slate-400 hover:text-[#1E6BFF] hover:bg-blue-50 transition-colors" title="Edit Kosakata">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Kosakata">
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
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                Belum ada kosakata terdaftar. Klik tombol <strong>+ Tambah Kosakata</strong> untuk menambahkan kosakata baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
