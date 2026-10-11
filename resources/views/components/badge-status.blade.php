@props([
    'status' => 'draft',
])

@php
    $statusLower = strtolower(trim($status));
@endphp

@if ($statusLower === 'aktif')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold border border-emerald-100/60">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
        Aktif
    </span>
@elseif ($statusLower === 'draft')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-semibold">
        Draft
    </span>
@else
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-rose-600 text-[11px] font-semibold border border-rose-100/60">
        {{ ucfirst($status) }}
    </span>
@endif
