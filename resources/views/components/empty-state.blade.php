@props([
    'icon' => '📭',
    'title' => 'Belum ada data',
    'subtitle' => null,
    'actionText' => null,
    'actionUrl' => null,
    'colspan' => null,
])

@php
    $content = function() use ($icon, $title, $subtitle, $actionText, $actionUrl) {
@endphp
    <div class="flex flex-col items-center justify-center text-center max-w-sm mx-auto">
        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl mb-3 shrink-0">
            {{ $icon }}
        </div>
        <p class="font-bold text-slate-700 text-sm">{{ $title }}</p>
        @if ($subtitle)
            <p class="text-xs text-slate-400 mt-1 leading-relaxed">{{ $subtitle }}</p>
        @endif
        @if ($actionText && $actionUrl)
            <a href="{{ $actionUrl }}" class="mt-4 px-4 py-2 rounded-xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-semibold text-xs shadow-xs transition-all inline-flex items-center gap-1.5">
                <span>+</span> {{ $actionText }}
            </a>
        @endif
    </div>
@php
    };
@endphp

@if ($colspan)
    <tr>
        <td colspan="{{ $colspan }}" class="py-12 px-4 text-center text-slate-400">
            {{ $content() }}
        </td>
    </tr>
@else
    <div class="py-12 px-4 text-center text-slate-400">
        {{ $content() }}
    </div>
@endif
