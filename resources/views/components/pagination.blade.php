@props([
    'paginator' => null,
    'elements' => null,
])

@php
    // Kompatibilitas: jika dipanggil via komponen tag <x-pagination :paginator="$data" />
    if (!isset($elements) && $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator) {
        $elements = $paginator->elements();
    }
    $elements = $elements ?? [];
@endphp

@if ($paginator && ($paginator->hasPages() || (method_exists($paginator, 'total') && $paginator->total() > 0)))
    <div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row items-center justify-between gap-3.5 py-2.5 select-none']) }}>
        
        {{-- Keterangan Jumlah Data (Indonesia & Mudah Dipahami) --}}
        <div class="text-xs text-slate-500 font-medium flex items-center gap-1.5 order-2 sm:order-1">
            <span class="inline-block w-2 h-2 rounded-full bg-[#4EA87D] shrink-0"></span>
            @if (method_exists($paginator, 'total'))
                <span>Menampilkan</span>
                <span class="px-2 py-0.5 rounded-lg bg-slate-100 font-bold text-slate-800 tracking-tight text-[11px] border border-slate-200/50">
                    {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }}
                </span>
                <span>dari</span>
                <span class="font-bold text-slate-800">{{ number_format($paginator->total()) }}</span>
                <span>data</span>
            @else
                <span>Halaman <strong class="text-slate-800 font-bold">{{ $paginator->currentPage() }}</strong></span>
            @endif
        </div>

        {{-- Navigasi Tombol Pagination --}}
        @if ($paginator->hasPages())
            <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center gap-1 sm:gap-1.5 flex-wrap justify-center order-1 sm:order-2">
                {{-- Tombol Sebelumnya (Previous) --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50/80 border border-slate-200/60 text-slate-300 text-xs font-semibold cursor-not-allowed select-none">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span>Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="group inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#4EA87D] hover:border-[#4EA87D]/50 hover:bg-[#F0FDF4] text-xs font-semibold shadow-xs hover:shadow-sm transition-all select-none">
                        <svg class="w-3.5 h-3.5 shrink-0 text-slate-500 group-hover:text-[#4EA87D] group-hover:-translate-x-0.5 transition-all" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span>Sebelumnya</span>
                    </a>
                @endif

                {{-- Angka-angka Pagination --}}
                <div class="flex items-center gap-1">
                    @foreach ($elements as $element)
                        {{-- Separator "..." --}}
                        @if (is_string($element))
                            <span class="w-7 h-8 flex items-center justify-center text-xs font-bold text-slate-400 select-none">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Link Halaman --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="w-8 h-8 rounded-xl bg-gradient-to-r from-[#4EA87D] to-[#3E946B] text-white text-xs font-bold flex items-center justify-center shadow-[0_2px_8px_rgba(78,168,125,0.32)] select-none">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200/80 text-slate-600 hover:text-[#4EA87D] hover:border-[#4EA87D]/50 hover:bg-[#F0FDF4] text-xs font-semibold flex items-center justify-center shadow-xs hover:shadow-sm transition-all select-none">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>

                {{-- Tombol Selanjutnya (Next) --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="group inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:text-[#4EA87D] hover:border-[#4EA87D]/50 hover:bg-[#F0FDF4] text-xs font-semibold shadow-xs hover:shadow-sm transition-all select-none">
                        <span>Selanjutnya</span>
                        <svg class="w-3.5 h-3.5 shrink-0 text-slate-500 group-hover:text-[#4EA87D] group-hover:translate-x-0.5 transition-all" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50/80 border border-slate-200/60 text-slate-300 text-xs font-semibold cursor-not-allowed select-none">
                        <span>Selanjutnya</span>
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </span>
                @endif
            </nav>
        @endif
    </div>
@endif
