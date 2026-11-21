@props([
    'target' => 'materi', // 'materi' atau 'quiz'
    'text' => 'Import via AI',
])

<button type="button" 
        onclick="openAiImportModal('{{ $target }}')" 
        {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 px-4.5 py-2.5 rounded-2xl bg-white hover:bg-[#EAF5EF]/70 text-slate-700 hover:text-[#2E7352] border border-slate-200/90 hover:border-[#4EA87D] font-bold text-xs shadow-xs transition-all duration-150 cursor-pointer select-none whitespace-nowrap group shrink-0']) }}>
    <svg class="w-4 h-4 text-[#4EA87D] group-hover:scale-105 transition-transform shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
        <polyline points="14 2 14 8 20 8"></polyline>
        <line x1="12" y1="18" x2="12" y2="12"></line>
        <line x1="9" y1="15" x2="15" y2="15"></line>
    </svg>
    <span>{{ $slot->isNotEmpty() ? $slot : $text }}</span>
</button>
