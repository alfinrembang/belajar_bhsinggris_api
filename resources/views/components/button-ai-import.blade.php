@props([
    'target' => 'materi', // 'materi' atau 'quiz'
    'text' => 'Import via AI',
])

<button type="button" 
        onclick="openAiImportModal('{{ $target }}')" 
        {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 px-4.5 py-2.5 rounded-2xl bg-white hover:bg-indigo-50/60 text-indigo-600 hover:text-indigo-700 border border-indigo-200/90 hover:border-indigo-300 font-bold text-xs shadow-xs transition-all duration-150 cursor-pointer select-none whitespace-nowrap group shrink-0']) }}>
    <svg class="w-4 h-4 text-indigo-600 group-hover:scale-110 transition-transform shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"></path>
    </svg>
    <span>{{ $slot->isNotEmpty() ? $slot : $text }}</span>
</button>
