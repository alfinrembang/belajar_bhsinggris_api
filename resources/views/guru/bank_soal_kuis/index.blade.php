<x-layout 
    title="Bank Soal & Kuis - Study English Prima" 
    pageTitle="Bank Soal & Kuis" 
    pageSubtitle="Kelola paket kuis evaluasi, room ujian live, butir soal interaktif, dan kunci jawaban." 
    active="quiz"
>
    <!-- Pesan Flash Sukses / Error -->
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-base">✅</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold text-sm leading-none">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-base">⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold text-sm leading-none">&times;</button>
        </div>
    @endif

    <!-- Bar Aksi Atas: Pencarian, Filter & Tombol Buat Paket Kuis -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs">
        <form method="GET" action="{{ route('guru.quiz.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto flex-1">
            <!-- Pencarian Kuis -->
            <div class="relative flex-1 max-w-md">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="Cari judul kuis atau kode room..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
            </div>

            <!-- Filter Kategori -->
            <select name="kategori" onchange="this.form.submit()" class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Kategori</option>
                <option value="Grammar" {{ request('kategori') == 'Grammar' ? 'selected' : '' }}>Grammar Test</option>
                <option value="Vocabulary" {{ request('kategori') == 'Vocabulary' ? 'selected' : '' }}>Vocabulary Challenge</option>
                <option value="Reading" {{ request('kategori') == 'Reading' ? 'selected' : '' }}>Reading Comprehension</option>
                <option value="Speaking" {{ request('kategori') == 'Speaking' ? 'selected' : '' }}>Speaking & Expression</option>
                <option value="General" {{ request('kategori') == 'General' ? 'selected' : '' }}>General English</option>
            </select>

            <!-- Filter Kelas -->
            <select name="kelas" onchange="this.form.submit()" class="px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Kelas</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>

            @if(request('q') || request('kategori') || request('kelas'))
                <a href="{{ route('guru.quiz.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-rose-600 transition-colors flex items-center gap-1">
                    Reset
                </a>
            @endif
        </form>

        <!-- Tombol Tambah Kuis -->
        <x-button variant="create" href="{{ route('guru.quiz.create') }}">
            Buat Paket Kuis
        </x-button>
    </div>

    <!-- Ringkasan Statistik Kuis -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <x-stat-card title="Total Paket Kuis" :value="$totalQuiz . ' Paket'" icon="clipboard" color="blue" />
        <x-stat-card title="Total Butir Soal" :value="$totalSoal . ' Butir'" icon="help" color="purple" />
        <x-stat-card title="Paket Kuis Aktif" :value="$totalAktif . ' Kuis'" icon="rocket" color="emerald" />
    </div>

    <!-- Tabel Daftar Paket Kuis -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Daftar Paket Kuis & Room Ujian</h3>
            <span class="text-xs text-slate-400">Terhubung live dengan kuis & kode room aplikasi siswa</span>
        </div>

        <div class="w-full overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4 w-[36%] whitespace-nowrap">Nama Paket & Kode Room</th>
                        <th class="py-3 px-3 w-[13%] whitespace-nowrap">Kategori & Kelas</th>
                        <th class="py-3 px-3 w-[13%] whitespace-nowrap">Jumlah Soal</th>
                        <th class="py-3 px-3 w-[11%] whitespace-nowrap">Durasi</th>
                        <th class="py-3 px-3 w-[11%] whitespace-nowrap">Status</th>
                        <th class="py-3 px-3 w-[16%] text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse ($quizzes as $quiz)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl bg-[#EAF5EF] text-[#4EA87D] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ $quiz->kategori_inisial }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('guru.quiz.show', $quiz) }}" class="font-bold text-slate-800 hover:text-[#4EA87D] transition-colors truncate block" title="{{ $quiz->judul }}">
                                            {{ $quiz->judul }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="inline-flex items-center gap-1 font-mono text-[11px] font-bold text-[#4EA87D] bg-[#EAF5EF] px-2 py-0.5 rounded-md whitespace-nowrap">
                                                ROOM: {{ $quiz->kode_room }}
                                            </span>
                                            <button type="button" 
                                                    onclick="salinKodeRoom('{{ $quiz->kode_room }}', this)" 
                                                    class="text-slate-400 hover:text-[#4EA87D] transition-colors p-0.5" 
                                                    title="Salin Kode Room">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <div class="flex flex-col gap-0.5 items-start">
                                    <span class="px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 text-[11px] font-semibold whitespace-nowrap">
                                        {{ $quiz->kategori }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 whitespace-nowrap">
                                        Kelas: {{ $quiz->tingkat_kelas }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-slate-700 font-semibold whitespace-nowrap">
                                {{ $quiz->soals_count ?? $quiz->soals->count() }} Butir
                            </td>
                            <td class="py-3 px-3 text-slate-700 whitespace-nowrap font-medium">
                                {{ $quiz->durasi_menit }} Menit
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <x-badge-status :status="$quiz->status" />
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Detail / Pratinjau -->
                                    <a href="{{ route('guru.quiz.show', $quiz) }}" 
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" 
                                       title="Pratinjau & Hasil Siswa">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('guru.quiz.edit', $quiz) }}" 
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-[#4EA87D] hover:bg-[#EAF5EF] transition-colors" 
                                       title="Edit Paket Kuis">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>

                                    <!-- Hapus -->
                                    <button type="button" 
                                            onclick="konfirmasiHapus('{{ route('guru.quiz.destroy', $quiz) }}', '{{ addslashes($quiz->judul) }}', 'Paket kuis ini beserta seluruh butir soal ujiannya akan dihapus permanen.')"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" 
                                            title="Hapus Kuis">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-empty-state 
                            colspan="6" 
                            icon="📭" 
                            title="Belum ada paket kuis" 
                            subtitle="Buat paket kuis dan bagikan kode room ujian kepada siswa Anda."
                            actionText="Buat Paket Kuis Baru"
                            :actionUrl="route('guru.quiz.create')"
                        />
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quizzes->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $quizzes->links() }}
            </div>
        @endif
    </div>

    <!-- Script Salin Kode Room -->
    <script>
        function salinKodeRoom(kode, btn) {
            navigator.clipboard.writeText(kode).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = `<span class="text-[10px] text-emerald-600 font-bold">Tersalin!</span>`;
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                }, 1500);
            }).catch(err => {
                alert('Kode room: ' + kode);
            });
        }
    </script>
</x-layout>
