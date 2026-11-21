@props([
    'label',
    'value' => 0,
    'percentage' => null,
    'color' => 'blue', // blue, emerald, amber
    'subtext' => null,
    'max' => 100,
])

@php
    $score = (float) $value;
    $progressWidth = min(100, max(0, $percentage !== null ? (float) $percentage : $score));

    $colorScheme = match ($color) {
        'listening', 'darkgreen' => [
            'bar' => 'bg-[#3E946B]',
            'dot' => 'bg-[#3E946B]',
            'text' => 'text-[#3E946B]',
        ],
        'kuis', 'forest' => [
            'bar' => 'bg-[#256345]',
            'dot' => 'bg-[#256345]',
            'text' => 'text-[#256345]',
        ],
        'emerald', 'green', 'materi' => [
            'bar' => 'bg-[#4EA87D]',
            'dot' => 'bg-[#4EA87D]',
            'text' => 'text-[#4EA87D]',
        ],
        'amber', 'yellow' => [
            'bar' => 'bg-[#F59E0B]',
            'dot' => 'bg-[#F59E0B]',
            'text' => 'text-[#F59E0B]',
        ],
        'pink', 'rose' => [
            'bar' => 'bg-[#E11D48]',
            'dot' => 'bg-[#E11D48]',
            'text' => 'text-[#E11D48]',
        ],
        default => [
            'bar' => 'bg-[#4EA87D]',
            'dot' => 'bg-[#4EA87D]',
            'text' => 'text-[#4EA87D]',
        ],
    };
@endphp

<div class="space-y-1.5 p-3 rounded-2xl hover:bg-slate-50/80 transition-colors">
    <div class="flex items-center justify-between text-xs">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full {{ $colorScheme['dot'] }} shrink-0 shadow-xs"></span>
            <span class="font-medium text-slate-500 text-xs">{{ $label }}</span>
        </div>
        @if ($subtext)
            <span class="text-[11px] font-semibold text-slate-400">{{ $subtext }}</span>
        @endif
    </div>

    <div class="flex items-baseline justify-between">
        <div class="flex items-baseline gap-1">
            <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $value }}</span>
            <span class="text-xs font-semibold text-slate-400">/ {{ $max }}</span>
        </div>
        <span class="text-xs font-bold {{ $colorScheme['text'] }} font-mono">
            {{ round($progressWidth, 1) }}%
        </span>
    </div>

    <!-- Mini Progress Bar Pill sesuai Gambar 1 -->
    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
        <div class="{{ $colorScheme['bar'] }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $progressWidth }}%"></div>
    </div>
</div>
