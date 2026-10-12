@props([
    'isTuntas' => true,
    'score' => null,
    'kkm' => 75,
])

@php
    $lulus = $score !== null ? ((float) $score >= (float) $kkm) : (bool) $isTuntas;
@endphp

@if ($lulus)
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200/80 shadow-2xs">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
        Tuntas (≥{{ $kkm }})
    </span>
@else
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-[11px] font-bold border border-rose-200/80 shadow-2xs animate-pulse">
        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
        Remedial (<{{ $kkm }})
    </span>
@endif
