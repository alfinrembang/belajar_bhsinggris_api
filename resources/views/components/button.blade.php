@props([
    'variant' => 'submit', // create, edit, delete, show, submit, back, cancel
    'href' => null,
    'type' => null,
    'size' => null,        // 'sm', 'md', 'lg'
    'icon' => true,
    'iconOnly' => false,
])

@php
    $hasSlot = $slot->isNotEmpty();
    $isIconOnly = $iconOnly || (!$hasSlot && in_array($variant, ['show', 'edit', 'delete']));
    $buttonType = $type ?? ($variant === 'submit' ? 'submit' : 'button');
    $showIcon = $icon !== false && $icon !== 'false';

    $baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-150 cursor-pointer select-none whitespace-nowrap';

    $variantClasses = match ($variant) {
        'create' => $isIconOnly 
            ? 'p-2 rounded-xl bg-[#4EA87D] hover:bg-[#43966E] text-white shadow-xs' 
            : 'px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#4EA87D] to-[#3E946B] hover:from-[#43966E] hover:to-[#35825D] text-white font-semibold text-xs shadow-[0_4px_14px_rgba(78,168,125,0.25)] gap-2',
            
        'edit' => $isIconOnly 
            ? 'p-2 rounded-xl text-slate-400 hover:text-[#4EA87D] hover:bg-[#EAF5EF]' 
            : 'px-4 py-2 rounded-2xl bg-[#4EA87D] hover:bg-[#43966E] text-white font-semibold text-xs shadow-xs gap-2',
            
        'delete' => $isIconOnly 
            ? 'p-2 rounded-xl text-slate-400 hover:text-[#FB7185] hover:bg-[#FDF2F4]' 
            : 'px-4 py-2 rounded-2xl bg-[#FDF2F4] hover:bg-[#FCE7EC] text-[#E11D48] border border-[#F9C5D1] font-semibold text-xs gap-2',
            
        'show' => $isIconOnly 
            ? 'p-2 rounded-xl text-slate-400 hover:text-[#4EA87D] hover:bg-[#EAF5EF]' 
            : 'px-4 py-2 rounded-2xl bg-white border border-slate-200/80 text-slate-600 hover:bg-[#FAFDFB] font-semibold text-xs shadow-xs gap-2',
            
        'submit' => 'px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#4EA87D] to-[#3E946B] hover:from-[#43966E] hover:to-[#35825D] text-white font-bold text-xs shadow-[0_4px_14px_rgba(78,168,125,0.28)] gap-2',
        
        'pink' => 'px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#FB7185] to-[#F472B6] hover:from-[#F43F5E] hover:to-[#EC4899] text-white font-semibold text-xs shadow-[0_4px_14px_rgba(251,113,133,0.28)] gap-2',
        
        'back' => 'text-xs font-semibold text-slate-500 hover:text-[#4EA87D] gap-2',
        
        'cancel' => 'px-5 py-2.5 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs gap-1.5',
        
        default => 'px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold gap-2'
    };

    if ($size === 'sm' && !$isIconOnly) {
        $variantClasses .= ' !px-3.5 !py-1.5 !text-[11px]';
    } elseif ($size === 'lg' && !$isIconOnly) {
        $variantClasses .= ' !px-6 !py-3 !text-sm';
    }

    $defaultText = match ($variant) {
        'create' => 'Tambah Baru',
        'edit' => 'Edit',
        'delete' => 'Hapus',
        'show' => 'Lihat Detail',
        'submit' => 'Simpan Perubahan',
        'back' => 'Kembali',
        'cancel' => 'Batal',
        default => 'Tombol'
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
        @if($showIcon)
            @if($variant === 'create')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            @elseif($variant === 'edit')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            @elseif($variant === 'delete')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            @elseif($variant === 'show')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            @elseif($variant === 'submit')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
            @elseif($variant === 'back')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            @endif
        @endif

        @if(!$isIconOnly)
            <span>{{ $hasSlot ? $slot : $defaultText }}</span>
        @endif
    </a>
@else
    <button type="{{ $buttonType }}" {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
        @if($showIcon)
            @if($variant === 'create')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            @elseif($variant === 'edit')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            @elseif($variant === 'delete')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            @elseif($variant === 'show')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            @elseif($variant === 'submit')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
            @elseif($variant === 'back')
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            @endif
        @endif

        @if(!$isIconOnly)
            <span>{{ $hasSlot ? $slot : $defaultText }}</span>
        @endif
    </button>
@endif
