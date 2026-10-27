@props([
    'number' => '01',
    'title',
    'subtitle' => null,
    'color' => 'blue',
])

@php
    $colorClass = [
        'blue' => 'bg-[#EAF5EF] text-[#2E7352]',
        'green' => 'bg-[#EAF5EF] text-[#2E7352]',
        'pink' => 'bg-[#FDF2F4] text-[#E11D48]',
        'purple' => 'bg-purple-50 text-purple-600',
        'emerald' => 'bg-[#EAF5EF] text-[#2E7352]',
        'amber' => 'bg-amber-50 text-amber-600',
    ][$color] ?? 'bg-[#EAF5EF] text-[#2E7352]';
@endphp

<div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl {{ $colorClass }} flex items-center justify-center font-bold text-sm shrink-0">
            {{ $number }}
        </div>
        <div>
            <h3 class="font-bold text-slate-800 text-base">{{ $title }}</h3>
            @if ($subtitle)
                <p class="text-xs text-slate-400">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if ($slot->isNotEmpty())
        <div class="self-start sm:self-auto shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>
