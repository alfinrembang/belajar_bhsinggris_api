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
                <svg class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold text-sm leading-none">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold text-sm leading-none">&times;</button>
        </div>
    @endif

    <!-- Tab Navigasi Sub-Modul Bank Soal & Kuis -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 bg-white p-3 rounded-2xl border border-slate-100 shadow-xs">
        <div class="flex items-center gap-1.5 flex-wrap">
            <a href="{{ route('guru.quiz.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-[#4EA87D] text-white shadow-xs">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                    <path d="M9 9h6"></path>
                    <path d="M9 13h6"></path>
                    <path d="M9 17h4"></path>
                    <path d="M12 9v1a1.5 1.5 0 0 0 1.5 1.5"></path>
                </svg>
                <span>Daftar Paket Kuis</span>
            </a>
            <a href="{{ route('guru.laporan.quiz') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span>Rekap Nilai Kuis</span>
            </a>
        </div>

        <span class="text-xs font-semibold text-slate-500 px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl">
            Modul Evaluasi Kuis Siswa
        </span>
    </div>

    <!-- Bar Aksi Atas: Pencarian, Filter & Tombol Aksi -->
    <div class="flex flex-col 2xl:flex-row 2xl:items-center justify-between gap-3.5 bg-white p-4 lg:p-5 rounded-3xl border border-slate-100 shadow-xs">
        <form method="GET" action="{{ route('guru.quiz.index') }}" class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 w-full 2xl:w-auto flex-1 min-w-0">
            <!-- Pencarian Kuis -->
            <div class="relative w-full sm:w-52 md:w-56 lg:w-60 shrink min-w-[140px]">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="Cari judul paket kuis..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
            </div>

            <!-- Filter Kategori (Dinamis Sesuai Manajemen Kategori) -->
            <select name="kategori" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D] shrink-0">
                <option value="">Semua Kategori</option>
                @if(isset($kategoriList) && $kategoriList->isNotEmpty())
                    @foreach($kategoriList as $kat)
                        @php $kNama = is_object($kat) ? $kat->nama : $kat; @endphp
                        <option value="{{ $kNama }}" {{ request('kategori') == $kNama ? 'selected' : '' }}>{{ $kNama }}</option>
                    @endforeach
                @else
                    <option value="Reading" {{ request('kategori') == 'Reading' ? 'selected' : '' }}>Reading</option>
                    <option value="Grammar" {{ request('kategori') == 'Grammar' ? 'selected' : '' }}>Grammar</option>
                    <option value="Conversation" {{ request('kategori') == 'Conversation' ? 'selected' : '' }}>Conversation</option>
                    <option value="Vocabulary" {{ request('kategori') == 'Vocabulary' ? 'selected' : '' }}>Vocabulary</option>
                @endif
                @if(request('kategori') && (!isset($kategoriList) || !$kategoriList->pluck('nama')->contains(request('kategori'))))
                    <option value="{{ request('kategori') }}" selected>{{ request('kategori') }}</option>
                @endif
            </select>

            <!-- Filter Rombel Kelas Terpadu -->
            <select name="rombel" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D] shrink-0">
                <option value="">Semua Kelas & Rombel</option>
                @if(isset($daftarRombelGrouped) && !empty($daftarRombelGrouped))
                    <option value="umum" {{ request('rombel') === 'umum' || request('rombel') === 'Semua Kelas' ? 'selected' : '' }}>
                        🌐 Umum (Semua Tingkat)
                    </option>
                    @foreach($daftarRombelGrouped as $tingkat => $rombelList)
                        <optgroup label="Tingkat Kelas {{ $tingkat }}">
                            <option value="tingkat:{{ $tingkat }}" {{ request('rombel') === 'tingkat:'.$tingkat || request('kelas') === (string)$tingkat ? 'selected' : '' }}>
                                📁 Semua Kelas {{ $tingkat }}
                            </option>
                            @foreach($rombelList as $rItem)
                                <option value="{{ $rItem }}" {{ request('rombel') === $rItem ? 'selected' : '' }}>
                                    {{ $rItem }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @endif
            </select>

            @if(request('q') || request('kategori') || request('rombel') || request('kelas'))
                <a href="{{ route('guru.quiz.index') }}" class="text-xs text-rose-600 hover:underline font-semibold py-2 px-1 shrink-0">
                    Reset
                </a>
            @endif
        </form>

        <!-- Tombol Aksi: Import via AI & Buat Paket Kuis -->
        <div class="flex items-center gap-2.5 flex-wrap shrink-0 self-start sm:self-end 2xl:self-auto">
            <x-button-ai-import target="quiz" />

            <x-button variant="create" href="{{ route('guru.quiz.create') }}">
                Buat Paket Kuis
            </x-button>
        </div>
    </div>

    <!-- Ringkasan Statistik Kuis -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <x-stat-card title="Total Paket Kuis" :value="$totalQuiz . ' Paket'" icon="clipboard" color="green" />
        <x-stat-card title="Total Butir Soal" :value="$totalSoal . ' Butir'" icon="help" color="green" />
        <x-stat-card title="Paket Kuis Aktif" :value="$totalAktif . ' Kuis'" icon="rocket" color="emerald" />
    </div>

    <!-- Tabel Daftar Paket Kuis -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Daftar Paket Kuis Evaluasi</h3>
            <span class="text-xs text-slate-400">Sinkron otomatis dengan menu kuis aplikasi siswa</span>
        </div>

        <div class="w-full overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-3.5 w-10 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                <input type="checkbox" 
                                       id="selectAllCheckbox" 
                                       onchange="togglePilihSemua(this)" 
                                       title="Centang untuk memilih seluruh paket kuis di halaman ini"
                                       class="w-4 h-4 rounded text-[#4EA87D] focus:ring-[#4EA87D]/20 border-slate-300 cursor-pointer transition-all">
                            </div>
                        </th>
                        <th class="py-3 px-4 w-[33%] whitespace-nowrap">Nama Paket Kuis</th>
                        <th class="py-3 px-3 w-[13%] whitespace-nowrap">Kategori & Kelas</th>
                        <th class="py-3 px-3 w-[12%] whitespace-nowrap">Jumlah Soal</th>
                        <th class="py-3 px-3 w-[11%] whitespace-nowrap">Durasi</th>
                        <th class="py-3 px-3 w-[11%] whitespace-nowrap">Status</th>
                        <th class="py-3 px-3 w-[16%] text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse ($quizzes as $quiz)
                        <tr class="hover:bg-slate-50/60 transition-colors" id="row-quiz-{{ $quiz->id }}">
                            <td class="py-3.5 px-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" 
                                           name="quiz_ids[]" 
                                           value="{{ $quiz->id }}" 
                                           data-judul="{{ $quiz->judul }}"
                                           onchange="onItemCheckboxChange()"
                                           class="quiz-item-checkbox w-4 h-4 rounded text-[#4EA87D] focus:ring-[#4EA87D]/20 border-slate-300 cursor-pointer transition-all">
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl bg-[#EAF5EF] text-[#4EA87D] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ $quiz->kategori_inisial }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('guru.quiz.show', $quiz) }}" class="font-bold text-slate-800 hover:text-[#4EA87D] transition-colors truncate block" title="{{ $quiz->judul }}">
                                            {{ $quiz->judul }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 truncate mt-0.5">
                                            {{ $quiz->deskripsi ?? 'Tidak ada ringkasan deskripsi' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <div class="flex flex-col gap-0.5 items-start">
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-semibold whitespace-nowrap border border-slate-200/60">
                                        {{ $quiz->kategori }}
                                    </span>
                                    @if(!empty($quiz->target_rombel_list))
                                        <div class="flex flex-wrap gap-1 max-w-[170px] mt-0.5">
                                            @foreach($quiz->target_rombel_list as $rBadge)
                                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200/60 whitespace-nowrap">{{ $rBadge }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400 whitespace-nowrap">
                                            @if($quiz->tingkat_kelas === 'Semua Kelas')
                                                Semua Kelas
                                            @else
                                                Kelas: {{ str_replace('Kelas ', '', $quiz->tingkat_kelas) }}
                                            @endif
                                        </span>
                                    @endif
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
                            colspan="7" 
                            icon="quiz" 
                            title="Belum ada paket kuis" 
                            subtitle="Buat paket kuis dan bagikan kode room ujian kepada siswa Anda."
                            actionText="Buat Paket Kuis Baru"
                            :actionUrl="route('guru.quiz.create')"
                        />
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quizzes->total() > 0)
            <div class="px-6 py-3.5 border-t border-slate-100">
                <x-pagination :paginator="$quizzes" />
            </div>
        @endif
    </div>

    <!-- Floating Bar Aksi Massal & Modal Hapus Kuis -->
    <x-bulk-delete-bar 
        :action="route('guru.quiz.bulk_destroy')"
        checkboxClass="quiz-item-checkbox"
        inputName="quiz_ids[]"
        itemLabel="paket kuis"
        :totalPageCount="$quizzes->count()"
        warningText="Seluruh butir soal ujian, riwayat pengerjaan, dan skor siswa pada paket kuis terpilih akan dihapus permanen."
    />

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

    <!-- Modal Reusable Import Dokumen AI -->
    <x-ai-import-modal />
</x-layout>
