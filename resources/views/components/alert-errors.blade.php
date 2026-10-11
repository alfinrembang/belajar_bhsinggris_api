@props([
    'title' => 'Mohon lengkapi formulir dengan benar:'
])

@if ($errors->any())
    <div class="mb-6 p-4.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
        <p class="font-bold mb-1.5 flex items-center gap-1.5">
            <span>⚠️</span> {{ $title }}
        </p>
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
