@props([
    'label',
    'percentage' => 0,
    'averageScore' => null,
    'color' => 'blue',
    'icon' => '📖',
])

@php
    $percentageClamped = max(0, min(100, (float) $percentage));
    $barColors = [
        'blue' => 'bg-[#1E6BFF]',
        'purple' => 'bg-purple-600',
        'emerald' => 'bg-emerald-500',
        'amber' => 'bg-amber-500',
    ][$color] ?? 'bg-[#1E6BFF]';

    $textColors = [
        'blue' => 'text-[#1E6BFF]',
        'purple' => 'text-purple-600',
        'emerald' => 'text-emerald-600',
        'amber' => 'text-amber-600',
    ][$color] ?? 'text-[#1E6BFF]';
@endphp

<div class="space-y-2">
    <div class="flex items-center justify-between text-xs">
        <div class="flex items-center gap-2 font-semibold text-slate-700">
            <span class="text-sm">{{ $icon }}</span>
            <span>{{ $label }}</span>
        </div>
        <div class="flex items-center gap-2 font-mono">
            @if ($averageScore !== null)
                <span class="text-slate-400 font-normal">Rerata: <strong class="text-slate-700">{{ $averageScore }}</strong></span>
                <span class="text-slate-300">•</span>
            @endif
            <span class="font-bold {{ $textColors }}">{{ round($percentageClamped, 1) }}%</span>
        </div>
    </div>
    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden shadow-inner">
        <div class="h-full {{ $barColors }} rounded-full transition-all duration-500" style="width: {{ $percentageClamped }}%"></div>
    </div>
</div>
