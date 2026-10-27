@props([
    'index',
    'soal' => [],
    'isQuiz' => false,
])

@php
    $idx = $index;
    $s = $soal;
    $kunci = strtolower($s['kunci_jawaban'] ?? 'a');
@endphp

<div class="soal-item p-5 rounded-2xl border border-slate-200/90 bg-slate-50/50 space-y-4 relative transition-all hover:border-slate-300">
    <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
        <span class="px-3 py-1 rounded-xl text-xs font-black bg-white text-slate-800 border border-slate-200/80 shadow-2xs soal-index-badge">
            Soal #{{ (int)$idx + 1 }}
        </span>
        <button type="button" 
                onclick="removeQuestion(this)" 
                class="text-xs text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1 cursor-pointer">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
            Hapus Soal
        </button>
    </div>

    @if($isQuiz)
        <!-- Reading Passage Opsional -->
        <div class="space-y-1">
            <label class="block text-[11px] font-bold text-slate-500">Teks Bacaan Terkait (Reading Passage - Opsional)</label>
            <textarea name="soals[{{ $idx }}][reading_passage]" 
                      rows="2" 
                      placeholder="Isi jika soal ini mengacu pada suatu teks cerita/dialog..." 
                      class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 outline-none">{{ $s['reading_passage'] ?? '' }}</textarea>
        </div>
    @endif

    <!-- Pertanyaan -->
    <div class="space-y-1">
        <label class="block text-xs font-bold text-slate-700">Kalimat Pertanyaan <span class="text-rose-500">*</span></label>
        <textarea name="soals[{{ $idx }}][pertanyaan]" 
                  rows="2" 
                  required 
                  class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">{{ $s['pertanyaan'] ?? '' }}</textarea>
    </div>

    <!-- Pilihan Jawaban A, B, C, D -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
        @foreach(['a', 'b', 'c', 'd'] as $opt)
            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilihan {{ strtoupper($opt) }}</label>
                <input type="text" 
                       name="soals[{{ $idx }}][pilihan_{{ $opt }}]" 
                       value="{{ $s['pilihan_' . $opt] ?? '' }}" 
                       required 
                       class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
            </div>
        @endforeach
    </div>

    <!-- Kunci Jawaban & Pembahasan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-200/60">
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kunci Jawaban <span class="text-rose-500">*</span></label>
            <select name="soals[{{ $idx }}][kunci_jawaban]" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-800 focus:border-[#1E6BFF] outline-none cursor-pointer">
                <option value="A" {{ $kunci === 'a' ? 'selected' : '' }}>A</option>
                <option value="B" {{ $kunci === 'b' ? 'selected' : '' }}>B</option>
                <option value="C" {{ $kunci === 'c' ? 'selected' : '' }}>C</option>
                <option value="D" {{ $kunci === 'd' ? 'selected' : '' }}>D</option>
            </select>
        </div>
        <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Penjelasan / Pembahasan Singkat</label>
            <input type="text" 
                   name="soals[{{ $idx }}][pembahasan]" 
                   value="{{ $s['pembahasan'] ?? '' }}" 
                   placeholder="Alasan mengapa jawaban tersebut benar..." 
                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
        </div>
    </div>
</div>
