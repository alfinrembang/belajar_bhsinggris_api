@props([
    'title',
    'value',
    'icon' => 'clipboard',
    'color' => 'blue',
])

@php
    $colorConfig = [
        'blue' => [
            'bg' => 'bg-[#EAF5EF]',
            'text' => 'text-[#2E7352]',
            'border' => 'border-[#D7EDE1]',
        ],
        'green' => [
            'bg' => 'bg-[#EAF5EF]',
            'text' => 'text-[#2E7352]',
            'border' => 'border-[#D7EDE1]',
        ],
        'pink' => [
            'bg' => 'bg-[#FDF2F4]',
            'text' => 'text-[#E11D48]',
            'border' => 'border-[#F9C5D1]',
        ],
        'purple' => [
            'bg' => 'bg-purple-50/80',
            'text' => 'text-purple-600',
            'border' => 'border-purple-100/60',
        ],
        'emerald' => [
            'bg' => 'bg-emerald-50/80',
            'text' => 'text-emerald-600',
            'border' => 'border-emerald-100/60',
        ],
        'amber' => [
            'bg' => 'bg-amber-50/80',
            'text' => 'text-amber-600',
            'border' => 'border-amber-100/60',
        ],
        'rose' => [
            'bg' => 'bg-[#FDF2F4]',
            'text' => 'text-[#E11D48]',
            'border' => 'border-[#F9C5D1]',
        ],
    ][$color] ?? [
        'bg' => 'bg-[#EAF5EF]',
        'text' => 'text-[#2E7352]',
        'border' => 'border-[#D7EDE1]',
    ];

    $iconName = trim((string)$icon);
@endphp

<div class="bg-white p-4.5 sm:p-5 rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-3.5 transition-all hover:shadow-sm">
    <div class="w-11 h-11 rounded-xl {{ $colorConfig['bg'] }} {{ $colorConfig['text'] }} border {{ $colorConfig['border'] }} flex items-center justify-center shrink-0">
        @if(str_contains($iconName, '<svg'))
            {!! $iconName !!}
        @elseif(in_array($iconName, ['book', 'materi', '📚']))
            <!-- Book / Modul Materi Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
        @elseif(in_array($iconName, ['check', 'check-circle', 'active', '✅']))
            <!-- Check Circle / Aktif Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        @elseif(in_array($iconName, ['mobile', 'smartphone', 'flutter', 'phone', '📱']))
            <!-- Smartphone / Sync Flutter Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                <line x1="12" y1="18" x2="12.01" y2="18"></line>
            </svg>
        @elseif(in_array($iconName, ['headphones', 'audio', 'listening', 'track', '🎧']))
            <!-- Headphones / Audio Listening Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
            </svg>
        @elseif(in_array($iconName, ['file-text', 'file', 'pertanyaan', 'soal', '📝']))
            <!-- File Text / Latihan Soal Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        @elseif(in_array($iconName, ['clipboard', 'clipboard-check', 'quiz', 'paket', '📋']))
            <!-- Clipboard Check / Paket Kuis Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                <path d="M9 14l2 2 4-4"></path>
            </svg>
        @elseif(in_array($iconName, ['help', 'help-circle', 'question', 'butir', '❓']))
            <!-- Help Circle / Butir Soal Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        @elseif(in_array($iconName, ['rocket', 'sparkle', 'target', 'kuis-aktif', '🚀']))
            <!-- Sparkles / Target Rocket Alternative Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
        @elseif(in_array($iconName, ['chart', 'bar-chart', 'analitik', 'nilai', '📊']))
            <!-- Bar Chart / Nilai Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
        @elseif(in_array($iconName, ['zap', 'activity', 'lightning', 'xp', 'submit', '⚡']))
            <!-- Zap / Aktivitas & XP Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
            </svg>
        @elseif(in_array($iconName, ['alert', 'alert-triangle', 'warning', 'remedial', '⚠️']))
            <!-- Alert Triangle / Remedial Icon -->
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        @else
            <span class="text-base font-bold">{{ $icon }}</span>
        @endif
    </div>
    <div class="min-w-0">
        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider truncate">{{ $title }}</p>
        <h4 class="text-lg sm:text-xl font-extrabold text-slate-800 mt-0.5 truncate">{{ $value }}</h4>
    </div>
</div>
