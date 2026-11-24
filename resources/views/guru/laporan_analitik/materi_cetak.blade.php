<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekapitulasi Nilai Materi Siswa - Study English Prima</title>
    
    <!-- Google Fonts Inter & Tailwind CSS CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }
        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm 10mm 15mm 10mm;
            }
            body {
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 p-4 sm:p-8">

    <!-- Top Action Toolbar (Hanya Tampil di Layar Monitor, Hilang Saat Print) -->
    <div class="no-print max-w-5xl mx-auto mb-6 bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-3 text-xs text-slate-500">
            <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base">🖨️</span>
            <div>
                <strong class="text-slate-800 block text-sm">Pratinjau Rekapitulasi Nilai Latihan Materi Siswa</strong>
                <span>Pastikan orientasi cetak diatur ke mode Landscape untuk hasil optimal.</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.close()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-colors cursor-pointer">
                Tutup
            </button>
            <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-[#4EA87D] hover:bg-[#43966E] text-white text-xs font-bold shadow-md transition-all flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Sekarang (Ctrl + P)
            </button>
        </div>
    </div>

    <!-- Halaman Kertas Cetak Resmi -->
    <div class="max-w-5xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-md border border-slate-200 print:border-none print:shadow-none print:p-0 print:rounded-none">
        
        <!-- KOP LAPORAN RESMI -->
        <div class="border-b-2 border-slate-800 pb-5 mb-6 text-center">
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-wide uppercase">
                SMK STUDY ENGLISH PRIMA
            </h1>
            <h2 class="text-sm sm:text-base font-bold text-slate-700 tracking-wider uppercase mt-1">
                REKAPITULASI NILAI LATIHAN PEMAHAMAN MODUL MATERI
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Mata Pelajaran: <strong>Bahasa Inggris</strong> • Standar KKM: <strong>{{ $kkm }}</strong> • Modul: <strong>{{ $materiPilihan ? $materiPilihan->judul : 'Seluruh Modul Materi' }}</strong> • Kategori: <strong>{{ $kategoriPilihan ?: 'Semua Kategori' }}</strong>
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 text-[11px] text-slate-600 mt-2">
                <span>🗓️ Tanggal Cetak: <strong>{{ date('d F Y') }}</strong></span>
                <span>•</span>
                <span>Tingkat / Jurusan: <strong>{{ request('rombel') ?: (request('kelas') ? 'Kelas ' . request('kelas') : 'Semua Tingkat') }} {{ request('jurusan') ? '(' . request('jurusan') . ')' : '' }}</strong></span>
            </div>
        </div>

        <!-- Ringkasan Statistik Evaluasi Materi -->
        <div class="grid grid-cols-4 gap-3 mb-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center text-xs">
            <div>
                <span class="text-slate-400 block text-[10px] font-bold uppercase">Total Pengerjaan</span>
                <strong class="text-slate-800 text-sm font-black">{{ $totalProgres }} Aktivitas</strong>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] font-bold uppercase">Rerata Skor Materi</span>
                <strong class="text-slate-800 text-sm font-black">{{ $rataRataSkor }} / 100</strong>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] font-bold uppercase">Jumlah Tuntas</span>
                <strong class="text-emerald-700 text-sm font-black">{{ $totalTuntas }} Siswa</strong>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] font-bold uppercase">Perlu Remedial</span>
                <strong class="text-rose-700 text-sm font-black">{{ $totalRemedial }} Siswa</strong>
            </div>
        </div>

        <!-- TABEL DATA NILAI MATERI -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border border-slate-300 text-xs">
                <thead>
                    <tr class="bg-slate-100 text-slate-800 font-extrabold border-b border-slate-300 text-center text-[11px]">
                        <th class="py-2.5 px-3 border border-slate-300 w-10">No</th>
                        <th class="py-2.5 px-4 border border-slate-300 text-left">Nama Siswa</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-24">NIS</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-28">Rombel</th>
                        <th class="py-2.5 px-4 border border-slate-300 text-left">Modul Materi</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-24">Kategori</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-20">Skor</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-24">Status Belajar</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-24">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium">
                    @forelse($progresList as $index => $item)
                        @php
                            $isTuntas = ($item->skor ?? 0) >= $kkm;
                        @endphp
                        <tr class="{{ $loop->even ? 'bg-slate-50/50' : 'bg-white' }} text-center">
                            <td class="py-2 px-3 border border-slate-300 font-bold text-slate-500">{{ $index + 1 }}</td>
                            <td class="py-2 px-4 border border-slate-300 text-left font-bold text-slate-800">
                                {{ $item->siswa->nama_lengkap ?? 'Siswa' }}
                            </td>
                            <td class="py-2 px-3 border border-slate-300 font-mono text-slate-600">
                                {{ $item->siswa->nis ?? '-' }}
                            </td>
                            <td class="py-2 px-3 border border-slate-300 text-slate-700">
                                {{ $item->siswa->kelas_lengkap ?? '-' }}
                            </td>
                            <td class="py-2 px-4 border border-slate-300 text-left text-slate-800 font-semibold">
                                {{ $item->materi->judul ?? 'Modul Materi' }}
                            </td>
                            <td class="py-2 px-3 border border-slate-300 text-slate-600">
                                {{ $item->materi->kategori ?? 'Umum' }}
                            </td>
                            <td class="py-2 px-3 border border-slate-300 font-mono font-extrabold {{ $isTuntas ? 'text-slate-900' : 'text-rose-600' }}">
                                {{ $item->skor !== null ? $item->skor : 0 }}
                            </td>
                            <td class="py-2 px-3 border border-slate-300 font-semibold text-[11px] {{ $item->status === 'selesai' ? 'text-emerald-700' : 'text-amber-600' }}">
                                {{ $item->status === 'selesai' ? 'Selesai' : 'Progres' }}
                            </td>
                            <td class="py-2 px-3 border border-slate-300 font-bold text-[11px] {{ $isTuntas ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $isTuntas ? 'Tuntas' : 'Remedial' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-slate-400 border border-slate-300">
                                Tidak ada data pengerjaan latihan materi yang memenuhi filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Kolom Tanda Tangan Guru Pengampu & Kepala Sekolah -->
        <div class="mt-12 grid grid-cols-2 text-center text-xs text-slate-700 break-inside-avoid">
            <div>
                <p>Mengetahui,</p>
                <p class="font-bold">Kepala Sekolah</p>
                <div class="h-20"></div>
                <p class="font-bold underline">(........................................................)</p>
                <p class="text-slate-400 text-[11px] mt-0.5">NIP: ............................................</p>
            </div>
            <div>
                <p>Rembang, {{ date('d F Y') }}</p>
                <p class="font-bold">Guru Pengampu Bahasa Inggris</p>
                <div class="h-20"></div>
                <p class="font-bold underline">{{ auth()->user()->name ?? 'Guru Pengampu' }}</p>
                <p class="text-slate-400 text-[11px] mt-0.5">NIP: {{ auth()->user()->nip ?? '............................................' }}</p>
            </div>
        </div>

    </div>

</body>
</html>
