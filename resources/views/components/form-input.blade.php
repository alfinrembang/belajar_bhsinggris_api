@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
])

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1.5']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-xs font-bold text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-rose-500">*</span>
            @endif
            @if($hint)
                <span class="text-slate-400 font-normal">({{ $hint }})</span>
            @endif
        </label>
    @endif
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           id="{{ $name }}"
           value="{{ old($name, $value) }}" 
           @if($placeholder) placeholder="{{ $placeholder }}" @endif
           @if($required) required @endif
           {{ $attributes->except('class')->merge(['class' => 'w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs font-semibold text-slate-800 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all']) }}>
</div>
