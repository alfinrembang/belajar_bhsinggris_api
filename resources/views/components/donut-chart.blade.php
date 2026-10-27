@props([
    'centerValue' => 0,
    'centerLabel' => 'Rerata',
    'centerSubtext' => 'skala 100',
    'degMateri' => 108,
    'degListening' => 216,
    'size' => 'w-44 h-44',
])

@php
    $d1 = max(1, min(358, (float) $degMateri));
    $d2 = max($d1 + 1, min(359, (float) $degListening));
@endphp

<div class="relative {{ $size }} rounded-full p-4 flex items-center justify-center shrink-0 shadow-xs transition-transform duration-300 hover:scale-[1.02]"
     style="background: conic-gradient(
         #4EA87D 0deg calc({{ $d1 }}deg - 2deg),
         transparent calc({{ $d1 }}deg - 2deg) {{ $d1 }}deg,
         #FB7185 {{ $d1 }}deg calc({{ $d2 }}deg - 2deg),
         transparent calc({{ $d2 }}deg - 2deg) {{ $d2 }}deg,
         #F59E0B {{ $d2 }}deg 358deg,
         transparent 358deg 360deg
     );"
     role="img"
     aria-label="Grafik lingkaran rerata nilai: Teori Materi, Listening Audio, dan Kuis">
    
    <!-- Inner Cutout Circle (Donut Hole) -->
    <div class="w-full h-full bg-white rounded-full flex flex-col items-center justify-center shadow-inner text-center select-none p-2 border border-slate-100/60">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">
            {{ $centerLabel }}
        </span>
        <span class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
            {{ $centerValue }}
        </span>
        <span class="text-[11px] font-semibold text-slate-400 leading-none mt-0.5">
            {{ $centerSubtext }}
        </span>
    </div>
</div>
