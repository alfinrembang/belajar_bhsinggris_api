@props([
    'icon' => 'inbox',
    'title' => 'Belum ada data',
    'subtitle' => null,
    'actionText' => null,
    'actionUrl' => null,
    'colspan' => null,
])

@php
    $iconName = trim((string)$icon);
    $content = function() use ($iconName, $icon, $title, $subtitle, $actionText, $actionUrl) {
@endphp
    <div class="flex flex-col items-center justify-center text-center max-w-sm mx-auto">
        <div class="w-12 h-12 rounded-2xl bg-slate-100/90 text-slate-400 border border-slate-200/60 flex items-center justify-center mb-3 shrink-0">
            @if(str_contains($iconName, '<svg'))
                {!! $iconName !!}
            @elseif(in_array($iconName, ['headphones', 'audio', 'listening', '🎧']))
                <svg class="w-6 h-6 text-[#FB7185]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                </svg>
            @elseif(in_array($iconName, ['book', 'materi', '📚']))
                <svg class="w-6 h-6 text-[#4EA87D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            @elseif(in_array($iconName, ['clipboard', 'quiz', 'kuis', '📋']))
                <svg class="w-6 h-6 text-[#4EA87D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <path d="M9 14l2 2 4-4"></path>
                </svg>
            @else
                <svg class="w-6 h-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                    <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                </svg>
            @endif
        </div>
        <p class="font-bold text-slate-700 text-sm">{{ $title }}</p>
        @if ($subtitle)
            <p class="text-xs text-slate-400 mt-1 leading-relaxed">{{ $subtitle }}</p>
        @endif
        @if ($actionText && $actionUrl)
            <a href="{{ $actionUrl }}" class="mt-4 px-4 py-2 rounded-xl bg-gradient-to-r from-[#4EA87D] to-[#3E946B] hover:from-[#43966E] hover:to-[#35825D] text-white font-semibold text-xs shadow-xs transition-all inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>{{ $actionText }}</span>
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
