@props([
    'cancelUrl',
    'cancelText' => 'Batal',
    'submitText' => 'Konfirmasi & Simpan',
    'statusNotice' => 'Pastikan data di atas sudah sesuai sebelum mengonfirmasi',
])

<!-- Floating Action Footer -->
<div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 p-4 shadow-lg">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 px-4 sm:px-6">
        <div class="flex items-center gap-3 text-xs text-slate-500">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>{{ $statusNotice }}</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ $cancelUrl }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                {{ $cancelText }}
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-[#1E6BFF] to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md shadow-blue-500/20 flex items-center gap-2 cursor-pointer transition-all active:scale-95">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ $submitText }}</span>
            </button>
        </div>
    </div>
</div>
