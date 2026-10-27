@props([
    'label',
    'percentage' => 0,
    'averageScore' => null,
    'color' => 'blue',
    'icon' => 'book',
])

@php
    $percentageClamped = max(0, min(100, (float) $percentage));
    $barColors = [
        'blue' => 'bg-[#4EA87D]',
        'green' => 'bg-[#4EA87D]',
        'pink' => 'bg-[#FB7185]',
        'purple' => 'bg-purple-600',
        'emerald' => 'bg-[#4EA87D]',
        'amber' => 'bg-amber-500',
    ][$color] ?? 'bg-[#4EA87D]';

    $textColors = [
        'blue' => 'text-[#4EA87D]',
        'green' => 'text-[#4EA87D]',
        'pink' => 'text-[#FB7185]',
        'purple' => 'text-purple-600',
        'emerald' => 'text-[#4EA87D]',
        'amber' => 'text-amber-600',
    ][$color] ?? 'text-[#4EA87D]';

    $iconName = trim((string)$icon);
@endphp

<div class="space-y-2">
    <div class="flex items-center justify-between text-xs">
        <div class="flex items-center gap-2 font-semibold text-slate-700">
            <span class="w-5 h-5 rounded-md bg-slate-100 {{ $textColors }} flex items-center justify-center shrink-0">
                @if(str_contains($iconName, '<svg'))
                    {!! $iconName !!}
                @elseif(in_array($iconName, ['book', 'reading', 'materi', '📖']))
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                @elseif(in_array($iconName, ['headphones', 'audio', 'listening', '🎧']))
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                        <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                    </svg>
                @elseif(in_array($iconName, ['file-text', 'file', 'quiz', 'kuis', '📝']))
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                @else
                    <span class="text-xs">{{ $icon }}</span>
                @endif
            </span>
            <span>{{ $label }}</span>
        </div>
        <div class="flex items-center gap-2 font-mono">
            @if ($averageScore !== null)
                <span class="text-slate-400 font-normal text-[11px]">Rerata: <strong class="text-slate-700 font-semibold">{{ $averageScore }}</strong></span>
                <span class="text-slate-300">•</span>
            @endif
            <span class="font-bold {{ $textColors }} text-xs">{{ round($percentageClamped, 1) }}%</span>
        </div>
    </div>
    <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden shadow-inner">
        <div class="h-full {{ $barColors }} rounded-full transition-all duration-500" style="width: {{ $percentageClamped }}%"></div>
    </div>
</div>
