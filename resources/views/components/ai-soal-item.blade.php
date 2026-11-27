@props([
    'index',
    'soal' => [],
    'isQuiz' => false,
])

@php
    $idx = $index;
    $s = $soal;
    $rawTipe = strtolower((string) ($s['tipe_soal'] ?? 'multiple_choice'));
    $currentTipe = match ($rawTipe) {
        'true_false' => 'true_false',
        'reading' => 'reading',
        default => 'multiple_choice',
    };
    $isTrueFalse = $isQuiz && $currentTipe === 'true_false';
    $kunci = strtolower((string) ($s['kunci_jawaban'] ?? 'a'));
    $rawTfItems = is_array($s['pernyataan_items'] ?? null) ? $s['pernyataan_items'] : [];
    if (empty($rawTfItems)) {
        $rawTfItems = [
            ['no' => 1, 'teks' => '', 'kunci' => 'true'],
            ['no' => 2, 'teks' => '', 'kunci' => 'true'],
        ];
    }
@endphp

<div class="soal-item p-5 rounded-2xl border border-slate-200/90 bg-slate-50/50 space-y-4 relative transition-all hover:border-slate-300" data-ai-index="{{ $idx }}">
    <div class="flex items-center justify-between gap-2 flex-wrap border-b border-slate-200/60 pb-3">
        <span class="px-3 py-1 rounded-xl text-xs font-black bg-white text-slate-800 border border-slate-200/80 shadow-2xs soal-index-badge">
            Soal #{{ (int)$idx + 1 }}
        </span>

        <div class="flex items-center gap-3">
            @if($isQuiz)
                <select name="soals[{{ $idx }}][tipe_soal]"
                        onchange="toggleAiQuestionType(this, '{{ $idx }}')"
                        class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-[11px] font-semibold text-slate-700 outline-none cursor-pointer">
                    <option value="multiple_choice" {{ $currentTipe === 'multiple_choice' ? 'selected' : '' }}>📝 Pilihan Ganda (A-D)</option>
                    <option value="reading" {{ $currentTipe === 'reading' ? 'selected' : '' }}>📖 Reading (Teks + A-D)</option>
                    <option value="true_false" {{ $currentTipe === 'true_false' ? 'selected' : '' }}>✓✗ True / False (Tabel Pernyataan)</option>
                </select>
            @endif

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

    <!-- Pertanyaan / Instruksi -->
    <div class="space-y-1">
        <label id="ai-pertanyaan-label-{{ $idx }}" class="block text-xs font-bold text-slate-700">
            {{ $isTrueFalse ? 'Instruksi / Teks Pengantar Tabel (Opsional)' : 'Kalimat Pertanyaan' }}
            @unless($isTrueFalse) <span class="text-rose-500">*</span> @endunless
        </label>
        <textarea id="ai-pertanyaan-input-{{ $idx }}"
                  name="soals[{{ $idx }}][pertanyaan]" 
                  rows="2" 
                  {{ $isTrueFalse ? '' : 'required' }}
                  placeholder="{{ $isTrueFalse ? 'Contoh: Tentukan apakah pernyataan berikut Benar (True) atau Salah (False)...' : 'Tuliskan pertanyaan...' }}"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">{{ $s['pertanyaan'] ?? '' }}</textarea>
    </div>

    @if($isQuiz)
        <!-- Tabel Pernyataan True / False -->
        <div id="ai-tf-wrapper-{{ $idx }}" class="{{ $isTrueFalse ? '' : 'hidden' }} p-4 rounded-2xl bg-[#EAF5EF]/60 border border-[#D7EDE1] space-y-3">
            <div class="flex items-center justify-between gap-2 flex-wrap">
                <div>
                    <p class="text-[11px] font-bold text-[#2E7352]">✓✗ Tabel Pernyataan True / False</p>
                    <p class="text-[10px] text-slate-500">Isi pernyataan di setiap baris dan tentukan kunci Benar (True) atau Salah (False).</p>
                </div>
                <button type="button"
                        onclick="addAiTrueFalseRow('{{ $idx }}')"
                        class="px-3 py-1.5 rounded-xl bg-white hover:bg-[#EAF5EF] text-[#2E7352] border border-[#C6E5D4] font-semibold text-[11px] transition-colors cursor-pointer">
                    + Tambah Baris
                </button>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200/80 bg-white">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200/70 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-2 px-3 w-10 text-center">No</th>
                            <th class="py-2 px-3">Pernyataan</th>
                            <th class="py-2 px-3 w-40">Kunci Jawaban</th>
                            <th class="py-2 px-2 w-10 text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="ai-tf-tbody-{{ $idx }}" data-next-row="{{ count($rawTfItems) }}" class="divide-y divide-slate-100 text-xs">
                        @foreach(array_values($rawTfItems) as $rIdx => $row)
                            @php
                                $rowKunci = \App\Models\QuizSoal::normalizeTrueFalseValue($row['kunci'] ?? 'true') ?? 'true';
                            @endphp
                            <tr class="ai-tf-row hover:bg-slate-50/60">
                                <td class="py-2 px-3 text-center font-bold text-slate-400 ai-tf-row-num">{{ $rIdx + 1 }}</td>
                                <td class="py-2 px-3">
                                    <input type="text"
                                           name="soals[{{ $idx }}][pernyataan_items][{{ $rIdx }}][teks]"
                                           value="{{ $row['teks'] ?? '' }}"
                                           placeholder="Tuliskan pernyataan ke-{{ $rIdx + 1 }}..."
                                           class="w-full px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                                </td>
                                <td class="py-2 px-3">
                                    <select name="soals[{{ $idx }}][pernyataan_items][{{ $rIdx }}][kunci]"
                                            class="w-full px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-bold {{ $rowKunci === 'true' ? 'text-emerald-700' : 'text-rose-600' }} outline-none cursor-pointer"
                                            onchange="this.classList.toggle('text-emerald-700', this.value === 'true'); this.classList.toggle('text-rose-600', this.value === 'false');">
                                        <option value="true" {{ $rowKunci === 'true' ? 'selected' : '' }}>✓ True (Benar)</option>
                                        <option value="false" {{ $rowKunci === 'false' ? 'selected' : '' }}>✗ False (Salah)</option>
                                    </select>
                                </td>
                                <td class="py-2 px-2 text-center">
                                    <button type="button"
                                            onclick="removeAiTrueFalseRow(this)"
                                            class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                            title="Hapus Baris">×</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Pilihan Jawaban A, B, C, D -->
    <div id="ai-abcd-wrapper-{{ $idx }}" class="{{ $isTrueFalse ? 'hidden' : '' }} grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
        @foreach(['a', 'b', 'c', 'd'] as $opt)
            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilihan {{ strtoupper($opt) }}</label>
                <input type="text" 
                       name="soals[{{ $idx }}][pilihan_{{ $opt }}]" 
                       value="{{ $s['pilihan_' . $opt] ?? '' }}" 
                       {{ $isTrueFalse ? '' : 'required' }}
                       class="ai-abcd-input-{{ $idx }} w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
            </div>
        @endforeach
    </div>

    <!-- Kunci Jawaban & Pembahasan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-200/60">
        <div id="ai-kunci-wrapper-{{ $idx }}" class="{{ $isTrueFalse ? 'hidden' : '' }}">
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kunci Jawaban <span class="text-rose-500">*</span></label>
            <select name="soals[{{ $idx }}][kunci_jawaban]" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-800 focus:border-[#1E6BFF] outline-none cursor-pointer">
                <option value="A" {{ $kunci === 'a' ? 'selected' : '' }}>A</option>
                <option value="B" {{ $kunci === 'b' ? 'selected' : '' }}>B</option>
                <option value="C" {{ $kunci === 'c' ? 'selected' : '' }}>C</option>
                <option value="D" {{ $kunci === 'd' ? 'selected' : '' }}>D</option>
            </select>
        </div>
        <div id="ai-pembahasan-wrapper-{{ $idx }}" class="{{ $isTrueFalse ? 'sm:col-span-3' : 'sm:col-span-2' }}">
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Penjelasan / Pembahasan Singkat</label>
            <input type="text" 
                   name="soals[{{ $idx }}][pembahasan]" 
                   value="{{ $s['pembahasan'] ?? '' }}" 
                   placeholder="Alasan mengapa jawaban tersebut benar..." 
                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
        </div>
    </div>
</div>
