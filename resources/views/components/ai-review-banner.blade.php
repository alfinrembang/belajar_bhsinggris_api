@props([
    'aiImport',
    'judul' => null,
    'backUrl' => null,
])

@php
    $effectiveBackUrl = $backUrl ?? route($aiImport->tipe_target === 'quiz' ? 'guru.quiz.index' : 'guru.materi.index');
    $displayJudul = $judul ?? ($aiImport->tipe_target === 'quiz' ? 'Paket Kuis Bahasa Inggris' : 'Materi Pembelajaran Bahasa Inggris');
    $fileSizeKb = number_format(($aiImport->ukuran_file ?? 0) / 1024, 1);
@endphp

<!-- Header Banner AI Document (Signature Royal Blue) -->
<div class="mb-6 bg-gradient-to-r from-[#1E6BFF] via-[#155BE5] to-[#0A56E2] rounded-3xl p-6 lg:p-8 text-white relative overflow-hidden shadow-[0_10px_30px_rgba(30,107,255,0.22)] border border-blue-400/20">
    <!-- Ambient Glow Dekoratif -->
    <div class="absolute -right-16 -top-16 w-72 h-72 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -bottom-20 w-56 h-56 rounded-full bg-blue-300/20 blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-2.5">
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-white/15 text-white border border-white/20 backdrop-blur-md">
                    Diproses dengan Google Gemini
                </span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-white/15 text-white border border-white/20 uppercase">
                    .{{ $aiImport->format_file }}
                </span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-400/20 text-emerald-100 border border-emerald-300/30">
                    Status: Selesai Diekstrak
                </span>
            </div>
            <h2 class="text-xl lg:text-2xl font-black tracking-tight text-white">
                {{ $displayJudul }}
            </h2>
            <p class="text-xs text-blue-100/90 max-w-2xl leading-relaxed font-normal">
                Dokumen sumber: <strong class="text-white font-semibold">{{ $aiImport->nama_file_asli }}</strong> ({{ $fileSizeKb }} KB).
                Periksa setiap bagian di bawah ini. Anda bebas mengubah teks, menambahkan butir soal, atau memperbaiki kunci jawaban sesuai kebutuhan kurikulum.
            </p>
        </div>

        <!-- Tombol Kembali -->
        <a href="{{ $effectiveBackUrl }}" 
           class="px-4 py-2.5 rounded-2xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold transition-all border border-white/20 shrink-0 flex items-center justify-center gap-2 cursor-pointer active:scale-95 shadow-sm">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Batalkan & Kembali
        </a>
    </div>
</div>
