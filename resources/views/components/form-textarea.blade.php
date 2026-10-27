@props([
    'name',
    'label' => null,
    'value' => null,
    'rows' => 3,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
    'fontMono' => false,
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
    <textarea name="{{ $name }}" 
              id="{{ $name }}"
              rows="{{ $rows }}"
              @if($placeholder) placeholder="{{ $placeholder }}" @endif
              @if($required) required @endif
              {{ $attributes->except('class')->merge(['class' => 'w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 leading-relaxed focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all' . ($fontMono ? ' font-mono' : '')]) }}>{{ old($name, $value) }}</textarea>
</div>
