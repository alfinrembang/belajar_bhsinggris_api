@props([
    'index',
    'number' => null,
    'soal' => null,
    'withReading' => false,
    'withAudio' => true,
    'withAudioTitle' => false,
    'audioFieldName' => 'audio_file',
    'existingAudioFieldName' => 'existing_audio_file',
    'audioWrapperId' => null,
    'audioPreviewId' => null,
    'onAudioChange' => null,
    'onTypeChange' => null,
    'onDelete' => 'hapusSoal(this)',
    'tipeOptions' => null,
])

@php
    $displayNumber = $number ?? (is_numeric($index) ? ((int)$index + 1) : $index);
    $currentTipe = old("soals.{$index}.tipe_soal", $soal?->tipe_soal ?? 'pilgan');
    $currentReading = old("soals.{$index}.reading_passage", $soal?->reading_passage ?? '');
    $currentAudioTitle = old("soals.{$index}.audio_title", $soal?->audio_title ?? '');
    $currentPertanyaan = old("soals.{$index}.pertanyaan", $soal?->pertanyaan ?? '');
    $currentA = old("soals.{$index}.pilihan_a", $soal?->pilihan_a ?? '');
    $currentB = old("soals.{$index}.pilihan_b", $soal?->pilihan_b ?? '');
    $currentC = old("soals.{$index}.pilihan_c", $soal?->pilihan_c ?? '');
    $currentD = old("soals.{$index}.pilihan_d", $soal?->pilihan_d ?? '');
    $currentKunci = old("soals.{$index}.kunci_jawaban", $soal?->kunci_jawaban ?? 'A');
    $currentPembahasan = old("soals.{$index}.pembahasan", $soal?->pembahasan ?? '');

    $existingAudioFile = null;
    $existingAudioUrl = null;
    if ($soal) {
        $existingAudioFile = $soal->{$audioFieldName} ?? null;
        if ($audioFieldName === 'audio_soal') {
            $existingAudioUrl = $soal->audio_soal_url ?? null;
        } else {
            $existingAudioUrl = $soal->audio_url ?? null;
        }
    }

    $effAudioWrapperId = $audioWrapperId ?? "listening-audio-wrapper-{$index}";
    $effAudioPreviewId = $audioPreviewId ?? "audio-preview-{$index}";
    $effOnTypeChange = $onTypeChange ?? "gantiTipeSoal(this, '{$index}')";
    $effOnAudioChange = $onAudioChange ?? "previewAudioSoal(this, '{$index}')";

    if (!is_array($tipeOptions)) {
        if ($withReading) {
            $tipeOptions = [
                'pilgan' => '📝 Pilihan Ganda Biasa',
                'reading' => '📖 Reading (Ada Teks Bacaan)',
                'listening' => '🎧 Listening (Ada Audio)',
            ];
        } else {
            $tipeOptions = [
                'pilgan' => '📝 Pilihan Ganda (Teks)',
                'listening' => '🎧 Listening (Ada Audio)',
            ];
        }
    }
@endphp

<div class="soal-card p-5 lg:p-6 rounded-2xl border border-slate-200/80 bg-slate-50/40 space-y-4" data-index="{{ $index }}">
    <div class="flex items-center justify-between pb-3 border-b border-slate-200/60">
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-xl bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center soal-number">
                {{ $displayNumber }}
            </span>
            <span class="font-bold text-slate-800 text-xs">Butir Pertanyaan</span>
        </div>

        <div class="flex items-center gap-3">
            <!-- Pilihan Tipe Soal -->
            @if(count($tipeOptions) > 1)
                <select name="soals[{{ $index }}][tipe_soal]" 
                        onchange="{{ $effOnTypeChange }}"
                        class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-[11px] font-semibold text-slate-700 outline-none cursor-pointer">
                    @foreach($tipeOptions as $val => $label)
                        <option value="{{ $val }}" {{ $currentTipe === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            @else
                <input type="hidden" name="soals[{{ $index }}][tipe_soal]" value="{{ array_key_first($tipeOptions) }}">
            @endif

            <!-- Tombol Hapus Butir -->
            <button type="button" onclick="{{ $onDelete }}" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Butir Soal">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Reading Passage (Tampil Jika Tipe = Reading) -->
    @if ($withReading)
        <div id="reading-passage-wrapper-{{ $index }}" class="{{ $currentTipe === 'reading' ? '' : 'hidden' }} p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/80 space-y-1.5">
            <label class="block text-[11px] font-bold text-amber-900">📖 Teks Bacaan / Passage (Reading):</label>
            <textarea name="soals[{{ $index }}][reading_passage]" 
                      rows="3" 
                      placeholder="Ketik atau tempel teks bacaan yang menjadi rujukan soal ini..."
                      class="w-full px-3 py-2 rounded-xl bg-white border border-amber-200 text-xs text-slate-800 outline-none">{{ $currentReading }}</textarea>
        </div>
    @endif

    <!-- Audio Listening (Tampil Jika Tipe = Listening) -->
    @if ($withAudio)
        <div id="{{ $effAudioWrapperId }}" class="{{ $currentTipe === 'listening' ? '' : 'hidden' }} p-3.5 rounded-xl bg-purple-50/70 border border-purple-200/80 space-y-2">
            <div class="flex items-center justify-between">
                <label class="block text-[11px] font-bold text-purple-900">🎧 File Audio Listening Soal (MP3/WAV/AAC):</label>
                <span class="text-[10px] text-purple-600 font-medium">Siswa memutar audio sebelum menjawab</span>
            </div>

            @if ($existingAudioFile)
                <input type="hidden" name="soals[{{ $index }}][{{ $existingAudioFieldName }}]" value="{{ $existingAudioFile }}">
                <div class="p-2 rounded-lg bg-white border border-purple-200 flex items-center justify-between text-xs">
                    <span class="text-purple-700 font-medium truncate max-w-xs">Audio Tersimpan: {{ basename($existingAudioFile) }}</span>
                    @if ($existingAudioUrl)
                        <audio controls class="h-7 w-48">
                            <source src="{{ $existingAudioUrl }}" type="audio/mpeg">
                        </audio>
                    @endif
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="{{ $withAudioTitle ? '' : 'sm:col-span-2' }}">
                    <input type="file" 
                           name="soals[{{ $index }}][{{ $audioFieldName }}]" 
                           accept="audio/*" 
                           onchange="{{ $effOnAudioChange }}"
                           class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 cursor-pointer w-full">
                    @if ($existingAudioFile)
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Biarkan kosong jika tidak ingin mengganti file audio</span>
                    @endif
                </div>
                @if ($withAudioTitle)
                    <div>
                        <input type="text" 
                               name="soals[{{ $index }}][audio_title]" 
                               value="{{ $currentAudioTitle }}"
                               placeholder="Judul/Topik percakapan audio (opsional)" 
                               class="w-full px-3 py-1.5 rounded-xl bg-white border border-purple-200 text-xs text-slate-800 outline-none">
                    </div>
                @endif
            </div>
            <audio id="{{ $effAudioPreviewId }}" controls class="hidden w-full h-8 mt-1"></audio>
        </div>
    @endif

    <!-- Teks Pertanyaan -->
    <div>
        <label class="block text-[11px] font-semibold text-slate-700 mb-1">
            Pertanyaan <span class="text-rose-500">*</span>
        </label>
        <input type="text" 
               name="soals[{{ $index }}][pertanyaan]" 
               value="{{ $currentPertanyaan }}"
               placeholder="Ketikkan kalimat pertanyaan kuis di sini..." 
               class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-200/80 text-xs text-slate-800 focus:border-[#4EA87D] outline-none">
    </div>

    <!-- 4 Pilihan Jawaban A, B, C, D -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
            <span class="font-bold text-xs text-slate-400 w-4">A.</span>
            <input type="text" name="soals[{{ $index }}][pilihan_a]" value="{{ $currentA }}" placeholder="Pilihan jawaban A" class="w-full text-xs text-slate-800 outline-none">
        </div>
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
            <span class="font-bold text-xs text-slate-400 w-4">B.</span>
            <input type="text" name="soals[{{ $index }}][pilihan_b]" value="{{ $currentB }}" placeholder="Pilihan jawaban B" class="w-full text-xs text-slate-800 outline-none">
        </div>
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
            <span class="font-bold text-xs text-slate-400 w-4">C.</span>
            <input type="text" name="soals[{{ $index }}][pilihan_c]" value="{{ $currentC }}" placeholder="Pilihan jawaban C" class="w-full text-xs text-slate-800 outline-none">
        </div>
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
            <span class="font-bold text-xs text-slate-400 w-4">D.</span>
            <input type="text" name="soals[{{ $index }}][pilihan_d]" value="{{ $currentD }}" placeholder="Pilihan jawaban D" class="w-full text-xs text-slate-800 outline-none">
        </div>
    </div>

    <!-- Kunci Jawaban & Pembahasan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
        <div>
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kunci Jawaban Benar:</label>
            <select name="soals[{{ $index }}][kunci_jawaban]" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-[#4EA87D] outline-none">
                <option value="A" {{ $currentKunci === 'A' ? 'selected' : '' }}>Kunci Jawaban: A</option>
                <option value="B" {{ $currentKunci === 'B' ? 'selected' : '' }}>Kunci Jawaban: B</option>
                <option value="C" {{ $currentKunci === 'C' ? 'selected' : '' }}>Kunci Jawaban: C</option>
                <option value="D" {{ $currentKunci === 'D' ? 'selected' : '' }}>Kunci Jawaban: D</option>
            </select>
        </div>
        <div class="sm:col-span-2">
            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pembahasan Singkat (Opsional):</label>
            <input type="text" name="soals[{{ $index }}][pembahasan]" value="{{ $currentPembahasan }}" placeholder="Penjelasan kenapa opsi ini tepat..." class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-700 outline-none">
        </div>
    </div>
</div>
