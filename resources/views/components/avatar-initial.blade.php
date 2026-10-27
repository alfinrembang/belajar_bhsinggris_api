@props([
    'name' => null,
    'initials' => null,
    'size' => 'sm',
])

@php
    $text = $initials;
    if (!$text && $name) {
        $words = preg_split('/\s+/', trim((string)$name));
        if (count($words) >= 2) {
            $text = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        } else {
            $text = strtoupper(mb_substr((string)$name, 0, 2));
        }
    }
    $text = $text ?: 'U';

    $sizeClasses = [
        'xs' => 'w-7 h-7 rounded-lg text-[10px]',
        'sm' => 'w-9 h-9 rounded-xl text-xs',
        'md' => 'w-11 h-11 rounded-2xl text-sm',
        'lg' => 'w-16 h-16 rounded-2xl text-2xl font-black',
    ][$size] ?? 'w-9 h-9 rounded-xl text-xs';
@endphp

<div {{ $attributes->merge(['class' => "{$sizeClasses} bg-gradient-to-br from-[#1E6BFF] to-[#0A56E2] text-white font-extrabold flex items-center justify-center shrink-0 shadow-xs select-none"]) }}>
    {{ $text }}
</div>
