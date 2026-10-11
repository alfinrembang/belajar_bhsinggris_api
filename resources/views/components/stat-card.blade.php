@props([
    'title',
    'value',
    'icon' => '📋',
    'color' => 'blue',
])

@php
    $colorClasses = [
        'blue' => 'bg-blue-50 text-[#1E6BFF]',
        'purple' => 'bg-purple-50 text-purple-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'rose' => 'bg-rose-50 text-rose-600',
    ][$color] ?? 'bg-blue-50 text-[#1E6BFF]';
@endphp

<div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
    <div class="w-12 h-12 rounded-2xl {{ $colorClasses }} flex items-center justify-center font-bold text-lg shrink-0">
        {{ $icon }}
    </div>
    <div>
        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">{{ $title }}</p>
        <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ $value }}</h4>
    </div>
</div>
