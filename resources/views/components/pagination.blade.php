@if ($paginator->hasPages() || $paginator->total() > 0)
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-100">
        
        <!-- Controls: Previous, Numbered Links, Next -->
        <div class="flex items-center gap-1 sm:gap-1.5 flex-wrap">
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-300 cursor-not-allowed select-none">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    <span>Previous</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-[#1E6BFF] hover:bg-slate-100 transition-colors select-none">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    <span>Previous</span>
                </a>
            @endif

            {{-- Angka-angka Pagination --}}
            <div class="flex items-center gap-1">
                @foreach ($elements as $element)
                    {{-- Separator "..." --}}
                    @if (is_string($element))
                        <span class="w-8 h-8 flex items-center justify-center text-xs font-bold text-slate-400 select-none">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Link Halaman --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="w-8 h-8 rounded-full bg-[#1E6BFF] text-white text-xs font-bold flex items-center justify-center shadow-[0_2px_8px_rgba(30,107,255,0.32)] select-none">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-8 h-8 rounded-full text-slate-600 hover:text-[#1E6BFF] hover:bg-slate-100 text-xs font-semibold flex items-center justify-center transition-colors select-none">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-[#1E6BFF] hover:bg-slate-100 transition-colors select-none">
                    <span>Next</span>
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-300 cursor-not-allowed select-none">
                    <span>Next</span>
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
            @endif
        </div>

        <!-- Showing results text (sesuai referensi desain) -->
        <div class="text-xs text-slate-500 font-medium select-none">
            Showing <strong class="text-slate-800 font-bold">{{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }}</strong> of <strong class="text-slate-800 font-bold">{{ number_format($paginator->total()) }}</strong> results
        </div>
    </nav>
@endif
