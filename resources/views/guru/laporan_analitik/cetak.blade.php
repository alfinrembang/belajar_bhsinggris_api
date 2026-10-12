<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekapitulasi Nilai Siswa - Study English Prima</title>
    <!-- Google Fonts: Inter / Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            @page {
                size: A4 landscape;
                margin: 1.5cm 1cm 1.5cm 1cm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-['Plus_Jakarta_Sans',sans-serif] text-slate-800 antialiased p-4 sm:p-8">

    <!-- Bar Navigasi Khusus Layar (Tidak Tercetak di Kertas) -->
    <div class="no-print max-w-5xl mx-auto mb-6 bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-lg">🖨️</span>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Pratinjau Lembar Rapor Leger Nilai</h3>
                <p class="text-xs text-slate-400">Pastikan opsi layout printer diatur ke <strong>Landscape</strong> untuk hasil terbaik.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.close()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-colors cursor-pointer">
                Tutup
            </button>
            <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white text-xs font-bold shadow-md transition-all flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Sekarang (Ctrl + P)
            </button>
        </div>
    </div>

    <!-- Halaman Kertas Resmi Penilaian -->
    <div class="max-w-5xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-md border border-slate-200 print:border-none print:shadow-none print:p-0 print:rounded-none">
        
        <!-- KOP LAPORAN RESMI -->
        <div class="border-b-2 border-slate-800 pb-5 mb-6 text-center">
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-wide uppercase">
                SMK STUDY ENGLISH PRIMA
            </h1>
            <h2 class="text-sm sm:text-base font-bold text-slate-700 tracking-wider uppercase mt-1">
                LEGER REKAPITULASI PENILAIAN HASIL BELAJAR SISWA
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Mata Pelajaran: <strong>Bahasa Inggris (English Core & Literacy)</strong> • Kriteria Ketuntasan Minimal (KKM): <strong>{{ $kkm }}</strong>
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 text-[11px] text-slate-600 mt-2">
                <span>🗓️ Tanggal Cetak: <strong>{{ date('d F Y') }}</strong></span>
                <span>•</span>
                <span>Tingkat / Jurusan: <strong>{{ request('kelas') ? 'Kelas ' . request('kelas') : 'Semua Tingkat' }} {{ request('jurusan') ? '(' . request('jurusan') . ')' : '' }}</strong></span>
            </div>
        </div>

        <!-- Ringkasan Statistik Penilaian Kelas -->
        <div class="grid grid-cols-4 gap-3 mb-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center text-xs">
            <div>
                <span class="text-slate-400 block text-[10px] font-bold uppercase">Total Siswa</span>
                <strong class="text-slate-800 text-sm font-black">{{ $totalSiswa }} Siswa</strong>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] font-bold uppercase">Rerata Nilai Kelas</span>
                <strong class="text-slate-800 text-sm font-black">{{ $rataRataKelas }} / 100</strong>
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

        <!-- TABEL DATA NILAI -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border border-slate-300 text-xs">
                <thead>
                    <tr class="bg-slate-100 text-slate-800 font-extrabold border-b border-slate-300 text-center text-[11px]">
                        <th class="py-2.5 px-3 border border-slate-300 w-10">No</th>
                        <th class="py-2.5 px-4 border border-slate-300 text-left">Nama Siswa</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-24">NIS</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-28">Rombel</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-20">Materi (30%)</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-20">Listening (30%)</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-20">Kuis (40%)</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-20">Nilai Akhir</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-24">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium">
                    @forelse($siswaCollection as $index => $item)
                        <tr class="{{ $loop->even ? 'bg-slate-50/50' : 'bg-white' }} text-center">
                            <td class="py-2 px-3 border border-slate-300 font-bold text-slate-500">{{ $index + 1 }}</td>
                            <td class="py-2 px-4 border border-slate-300 text-left font-bold text-slate-800">{{ $item['nama_lengkap'] }}</td>
                            <td class="py-2 px-3 border border-slate-300 font-mono text-slate-600">{{ $item['nis'] ?: '-' }}</td>
                            <td class="py-2 px-3 border border-slate-300 text-slate-700">{{ $item['kelas_lengkap'] ?: '-' }}</td>
                            <td class="py-2 px-3 border border-slate-300 font-mono">{{ $item['rata_materi'] }}</td>
                            <td class="py-2 px-3 border border-slate-300 font-mono">{{ $item['rata_listening'] }}</td>
                            <td class="py-2 px-3 border border-slate-300 font-mono">{{ $item['rata_quiz'] }}</td>
                            <td class="py-2 px-3 border border-slate-300 font-mono font-extrabold {{ $item['is_tuntas'] ? 'text-slate-900' : 'text-rose-600' }}">
                                {{ $item['nilai_akhir'] }}
                            </td>
                            <td class="py-2 px-3 border border-slate-300 font-bold text-[11px] {{ $item['is_tuntas'] ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $item['is_tuntas'] ? 'Tuntas' : 'Remedial' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-slate-400 border border-slate-300">
                                Tidak ada data siswa yang memenuhi filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Kolom Tanda Tangan Guru Pengajar & Kepala Sekolah -->
        <div class="mt-12 grid grid-cols-2 text-center text-xs text-slate-700 break-inside-avoid">
            <div>
                <p>Mengetahui,</p>
                <p class="font-bold text-slate-800">Kepala Sekolah</p>
                <div class="h-20"></div>
                <p class="font-extrabold underline text-slate-900">( .................................................... )</p>
                <p class="text-[10px] text-slate-400 mt-0.5">NIP: ............................................</p>
            </div>
            <div>
                <p>Rembang, {{ date('d F Y') }}</p>
                <p class="font-bold text-slate-800">Guru Pengampu Bahasa Inggris</p>
                <div class="h-20"></div>
                <p class="font-extrabold underline text-slate-900">{{ auth()->user()->name ?? '( Guru Pengajar )' }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">NIP: ............................................</p>
            </div>
        </div>

    </div>

</body>
</html>
