<x-layout 
    title="Nilai per Materi & Kategori - Study English Prima" 
    pageTitle="Laporan Nilai per Modul Materi" 
    pageSubtitle="Analisis detail perolehan nilai latihan pemahaman siswa berdasarkan modul materi dan kategori pembelajaran." 
    active="laporan"
>
    <!-- Tab Navigasi Sub-Modul Laporan & Analitik -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 bg-white p-3 rounded-2xl border border-slate-100 shadow-xs">
        <div class="flex items-center gap-1.5 flex-wrap">
            <a href="{{ route('guru.laporan.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all flex items-center gap-2">
                <span>📊</span> Leger Nilai Kolektif
            </a>
            <a href="{{ route('guru.laporan.materi') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-[#4EA87D] text-white shadow-xs">
                <span>📚</span> Nilai per Materi & Kategori
            </a>
            <a href="{{ route('guru.laporan.quiz') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all flex items-center gap-2">
                <span>📝</span> Rekap Nilai Kuis
            </a>
        </div>

        <span class="text-xs font-semibold text-slate-500 px-3 py-1 bg-slate-50 border border-slate-100 rounded-xl">
            Standar KKM: <strong class="text-slate-800">{{ $kkm }}</strong>
        </span>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white p-5 lg:p-6 rounded-3xl border border-slate-100 shadow-xs mb-6">
        <form method="GET" action="{{ route('guru.laporan.materi') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Pencarian Siswa -->
            <div class="relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Cari nama / NIS..." 
                       class="w-full pl-9 pr-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
            </div>

            <!-- Filter Kategori Materi -->
            <select name="kategori" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Kategori</option>
                @foreach($daftarKategori as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>

            <!-- Filter Judul Materi -->
            <select name="materi_id" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Modul Materi</option>
                @foreach($daftarMateri as $mat)
                    <option value="{{ $mat->id }}" {{ request('materi_id') == $mat->id ? 'selected' : '' }}>
                        {{ Str::limit($mat->judul, 25) }}
                    </option>
                @endforeach
            </select>

            <!-- Filter Tingkat Kelas -->
            <select name="kelas" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                <option value="">Semua Kelas</option>
                <option value="10" {{ request('kelas') == '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('kelas') == '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('kelas') == '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>

            <!-- Filter Jurusan -->
            <div class="flex items-center gap-2">
                <select name="jurusan" onchange="this.form.submit()" class="flex-1 px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-700 outline-none cursor-pointer focus:border-[#4EA87D]">
                    <option value="">Semua Jurusan</option>
                    @foreach($daftarJurusan as $jur)
                        <option value="{{ $jur }}" {{ request('jurusan') == $jur ? 'selected' : '' }}>{{ $jur }}</option>
                    @endforeach
                </select>

                @if(request('q') || request('kategori') || request('materi_id') || request('kelas') || request('jurusan'))
                    <a href="{{ route('guru.laporan.materi') }}" 
                       class="p-2.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition-colors"
                       title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Ringkasan Statistik Nilai Materi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                📚
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Evaluasi</p>
                <h3 class="text-xl font-black text-slate-800">{{ $totalProgres }} Data</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                ⭐
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rerata Skor Materi</p>
                <h3 class="text-xl font-black text-slate-800">{{ $rataRataSkor }} <span class="text-xs text-slate-400 font-medium">/ 100</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#EAF5EF] text-[#2E7352] flex items-center justify-center font-bold text-lg">
                ✓
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Selesai Membaca</p>
                <h3 class="text-xl font-black text-emerald-700">{{ $totalSelesai }} Siswa</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                🎯
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tuntas KKM (≥{{ $kkm }})</p>
                <h3 class="text-xl font-black text-purple-700">{{ $totalTuntas }} Siswa</h3>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Nilai per Materi & Kategori -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-slate-800 text-sm">Rincian Nilai Pemahaman Materi</h3>
                <p class="text-xs text-slate-400 mt-0.5">Daftar siswa yang telah menyelesaikan materi dan latihan soal interaktif</p>
            </div>
            <span class="text-xs font-bold px-3 py-1 bg-slate-100 rounded-full text-slate-600">
                Menampilkan: {{ $progresList->count() }} dari {{ $progresList->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">No</th>
                        <th class="py-3.5 px-6">Nama Siswa & NIS</th>
                        <th class="py-3.5 px-6">Rombel Kelas</th>
                        <th class="py-3.5 px-6">Judul Materi</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6 text-center">Skor Latihan</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6">Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($progresList as $index => $progres)
                        @php
                            $isTuntas = ($progres->skor ?? 0) >= $kkm;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-slate-400 font-semibold">
                                {{ $progresList->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ $progres->siswa ? route('guru.siswa.show', $progres->siswa->id) : '#' }}" 
                                   class="font-bold text-slate-800 text-sm hover:text-[#4EA87D] transition-colors">
                                    {{ $progres->siswa->nama_lengkap ?? 'Siswa tidak ditemukan' }}
                                </a>
                                <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                                    NIS: {{ $progres->siswa->nis ?? '-' }}
                                </p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-0.5 rounded-md bg-[#EAF5EF] text-[#2E7352] font-semibold text-xs border border-[#D7EDE1]/70">
                                    {{ $progres->siswa->kelas_lengkap ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                {{ $progres->materi->judul ?? 'Materi telah dihapus' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                    {{ $progres->materi->kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center font-mono font-extrabold text-sm {{ $isTuntas ? 'text-[#4EA87D]' : 'text-rose-600' }}">
                                {{ $progres->skor !== null ? $progres->skor : '-' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($progres->status === 'selesai')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $isTuntas ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $isTuntas ? '✓ Tuntas' : '⚠️ Remedial' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700">
                                        Proses
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-[11px] text-slate-500">
                                {{ $progres->completed_at ? $progres->completed_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <span class="text-3xl block mb-2">📚</span>
                                Belum ada data nilai pengerjaan materi yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($progresList->hasPages())
            <div class="p-6 border-t border-slate-100">
                {{ $progresList->links() }}
            </div>
        @endif
    </div>
</x-layout>
