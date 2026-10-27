@props([
    'kategori' => 'General',
    'size' => 'text-[11px] px-2.5 py-0.5',
])

@php
    $kat = trim((string) $kategori);
    $badgeClasses = match (strtolower($kat)) {
        'reading' => 'bg-[#EAF5EF] text-[#2E7352] border-[#D7EDE1]',
        'grammar' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'conversation' => 'bg-purple-50 text-purple-700 border-purple-100',
        'vocabulary' => 'bg-amber-50 text-amber-700 border-amber-100',
        'writing' => 'bg-slate-100 text-slate-700 border-slate-200',
        'listening' => 'bg-[#FDF2F4] text-[#E11D48] border-[#F9C5D1]',
        'speaking' => 'bg-rose-50 text-rose-700 border-rose-100',
        default => 'bg-slate-50 text-slate-700 border-slate-200',
    };
@endphp

<span class="inline-flex items-center font-semibold rounded-md border whitespace-nowrap {{ $size }} {{ $badgeClasses }}">
    {{ $kat ?: 'General' }}
</span>
